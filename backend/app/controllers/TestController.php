<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \App\Models\Test;

class TestController extends BaseController {
  private Test $test;

  public function __construct(Test $model) {
    $this->test = $model;
  }

  public function test(Response $response, $param) : Response {
    $response->getBody()->write($this->test->test($param));
    return $response;
  }

  public function test_post(Request $request, Response $response) : Response {
    $parsedBody = $request->getParsedBody();
    var_dump($parsedBody);
    $response->getBody()->write("");
    return $response;
  }

  public function test_autorize(Request $request, Response $response) : Response {
    $username = self::Autorize();
    if($username) $response->getBody()->write($username);
    else throw new \Slim\Exception\HttpForbiddenException($request, message: "kek");
    return $response;
  }
}