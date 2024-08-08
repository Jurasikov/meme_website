<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CommentController extends BaseController {
  private \App\Models\Comment $comment;
  private \App\Models\Post $post;

  public function __construct(\App\Models\Comment $comment, \App\Models\Post $post) {
    $this->comment = $comment;
    $this->post = $post;
  }

  public function get_comments(Request $request, Response $response, string $id) : Response {
    $response->getBody()->write(json_encode($this->comment->read_by_post((int)$id)));
    return $response;
  }

  public function add_comment(Request $request, Response $response, string $id) : Response {
    $userId = self::Autorize();
    if(!$userId) throw new \Slim\Exception\HttpForbiddenException($request);
    if(!is_numeric($id) || !$this->post->read_by_id((int)$id)) throw new \Slim\Exception\HttpBadRequestException($request);
    
    $commentContent = $request->getParsedBody()['content'];
    $replyTo = $request->getParsedBody()['replyTo'];

    $this->comment->create((int)$id, $userId, $replyTo, $commentContent);
    return $response->withStatus(201);
  }
}