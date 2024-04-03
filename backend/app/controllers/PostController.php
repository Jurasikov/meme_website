<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;
use \App\Models\Post;
use \App\Models\User;
use Firebase\JWT\JWT;

class PostController extends BaseController {
  private Post $post;
  private User $user;

  public function __construct(Post $post, User $user) {
    $this->post = $post;
    $this->user = $user;
  }

  public function add_post(Request $request, Response $response) : Response {
    $username = self::Autorize();
    if(!$username) throw new \Slim\Exception\HttpForbiddenException($request);

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

    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['media'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = $this->moveUploadedFile($username, $uploadedFile);
        $id = $this->user->read($username)['id'];
        $this->post->create($id, $title, $filename);
        $response->getBody()->write('Uploaded file');
        return $response->withStatus(201);
    }
    else return $response->withStatus(500);
  }

  public function get_posts(Request $request, Response $response) : Response {
    $params = $request->getQueryParams();
    $data = $this->post->read(intval($params['post_num']), intval($params['page']));
    $total_post_num = $this->post->row_count();
    $body = [
      "data" => $data,
      "total_post_number" => $total_post_num
    ];
    $response->getBody()->write(json_encode($body));
    return $response;
  }

  private function moveUploadedFile(string $username, UploadedFileInterface $uploadedFile) {
    $directory = $_ENV['UPLOAD_DIR'];
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    $basename = uniqid($username.'_', true);
    $filename = sprintf('%s.%s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
  }

}