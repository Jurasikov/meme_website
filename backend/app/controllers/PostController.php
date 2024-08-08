<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;
use \App\Models\Post;
use \App\Models\User;
use Firebase\JWT\JWT;

class PostController extends BaseController {
  private Post $post;
  private User $user;
  private \App\Models\Comment $comment;
  private \App\Models\Tag $tag;
  

  public function __construct(Post $post, User $user, \App\Models\Comment $comment, \App\Models\Tag $tag) {
    $this->post = $post;
    $this->user = $user;
    $this->comment = $comment;
    $this->tag = $tag;
  }

  public function add_post(Request $request, Response $response) : Response {
    $userId = self::Autorize();
    if(!$userId) throw new \Slim\Exception\HttpForbiddenException($request);

    try{
      $title = self::Validate($request->getParsedBody()['title'], maxLength: 100);
    }
    catch(\Exception $e) {
      $response->getBody()->write(json_encode([
        "message" => preg_replace("/input/", "Tytuł", $e->getMessage()),
        "field" => "title"
      ]));
      return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }

    $tags = $request->getParsedBody()['tags'] ?? [];

    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['media'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $username = $this->user->read_by_id($userId)['name'];
        $filename = $this->moveUploadedFile($username, $uploadedFile);
        try {
          $this->post->beginTransaction();
          $this->post->create($userId, $title, $filename);
          $postId = intval($this->post->lastInsertId());
          foreach($tags as $tag) {
            if(!$this->tag->read($tag))
              $this->tag->create($tag);
            $tagId = $this->tag->read($tag)['id'];
            $this->post->add_tag($postId, $tagId);
          }
          $this->post->commit();
        }
        catch (\Throwable $e) {
          $this->post->rollBack();
          unlink($_ENV['UPLOAD_DIR'] . DIRECTORY_SEPARATOR . $filename);
          throw $e;
        }
        $response->getBody()->write("Uploaded file id = $postId");
        return $response->withStatus(201);
    }
    else {
      $response->getBody()->write(strval($uploadedFile->getError()));
      return $response->withStatus(500);
    }
  }

  public function get_posts(Request $request, Response $response) : Response {
    $userId = self::Autorize();

    $params = $request->getQueryParams();
    $data = $this->post->read_latest(intval($params['post_num'] ?? 8), intval($params['page'] ?? 0), $userId);
    $total_post_num = $this->post->row_count();
    $body = [
      "data" => $data,
      "total_post_number" => $total_post_num
    ];
    $response->getBody()->write(json_encode($body));
    return $response;
  }

  public function get_post(Request $request, Response $response, string $id) : Response {
    $userId = self::Autorize();
    $post = $this->post->read_by_id((int)$id, $userId);
    if(!$post) throw new \Slim\Exception\HttpNotFoundException($request);
    $response->getBody()->write(json_encode($post));
    return $response;
  }

  public function add_vote(Request $request, Response $response, string $id) : Response {
    $userId = self::Autorize();
    if(!$userId) throw new \Slim\Exception\HttpForbiddenException($request);
    if(!is_numeric($id) || !$this->post->read_by_id((int)$id)) throw new \Slim\Exception\HttpBadRequestException($request);
    if($request->getParsedBody()['vote'] != 1 && $request->getParsedBody()['vote'] != -1) throw new \Slim\Exception\HttpBadRequestException($request);

    if($this->post->read_reaction((int) $id, $userId)) $this->post->update_reaction((int) $id, $userId, $request->getParsedBody()['vote']);
    else {
      $this->post->create_reaction((int) $id, $userId, $request->getParsedBody()['vote']);
      $response = $response->withStatus(201);
    }

    $response->getBody()->write(json_encode(["ratio" => $this->post->read_ratio((int) $id), "vote" => $this->post->read_reaction((int) $id, $userId)['value']]));
    return $response;
  }

  public function remove_vote(Request $request, Response $response, string $id) : Response {
    $userId = self::Autorize();
    if(!$userId) throw new \Slim\Exception\HttpForbiddenException($request);
    if(!is_numeric($id) || !$this->post->read_by_id((int)$id)) throw new \Slim\Exception\HttpBadRequestException($request);

      $this->post->delete_reaction((int) $id, $userId);

      $response->getBody()->write(json_encode(["ratio" => $this->post->read_ratio((int) $id), "vote" => 0]));
      return $response;
  }

  private function moveUploadedFile(string $username, UploadedFileInterface $uploadedFile) : String {
    $directory = $_ENV['UPLOAD_DIR'];
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    $basename = uniqid($username.'_', true);
    $filename = sprintf('%s.%s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
  }

}