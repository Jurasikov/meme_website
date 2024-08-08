<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \App\Models\User;
use Firebase\JWT\JWT;

class UserController extends BaseController {
  private User $user;

  public function __construct(User $model) {
    $this->user = $model;
  }

  public function generate_token(int $userId, $expirationDate) {
    $payload = [
      'iss' => $_SERVER['SERVER_NAME'],
      'userId' => $userId,
      'iat' => time(),
      'exp' => $expirationDate
    ];
    $token = JWT::encode($payload, $_ENV['JWT_KEY'], $_ENV['JWT_ALG']);
    return $token;
  }

  public function register_user(Request $request, Response $response) : Response {
    $parsedBody = $request->getParsedBody();

    try{
      $username = self::Validate($parsedBody['username'], 4, 20, true);
    }
    catch(\Exception $e) {
      $response->getBody()->write(json_encode([
        "message" => preg_replace("/input/", "Nazwa użytkownika", $e->getMessage()),
        "field" => "username"
      ]));
      return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }

    try{
      $password = self::Validate($parsedBody['password'], 4, 60);
    }
    catch(\Exception $e) {
      $response->getBody()->write(json_encode([
        "message" => preg_replace("/input/", "Hasło", $e->getMessage()),
        "field" => "password"
      ]));
      return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }

    $user = $this->user->read($username);
    if(!$user) {
      $userId = $this->user->create($username, $password, false);
      
      $expirationDate = time() + 60*60*24;
      $token = $this->generate_token($userId, $expirationDate);
      setcookie("user_token", $token, $expirationDate, '/', httponly: true);

      $userData = json_encode([
        'username' => $username
      ]);
      setcookie("user_data", $userData, $expirationDate, '/');
    }
    else{
      $message = "podana nazwa użytkownika jest już zajęta";
      $response->getBody()->write(json_encode(['message' => $message]));
      return $response->withHeader('Content-type', 'application/json')->withStatus(409);
    }
    return $response->withStatus(201);
  }

  public function login(Request $request, Response $response) : Response {
    $parsedBody = $request->getParsedBody();

    try{
      $username = self::Validate($parsedBody['username'], 4, 20);
    }
    catch(\Exception $e) {
      $response->getBody()->write(json_encode([
        "message" => preg_replace("/input/", "Nazwa użytkownika", $e->getMessage()),
        "field" => "username"
      ]));
      return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }

    try{
      $password = self::Validate($parsedBody['password'], 4, 60);
    }
    catch(\Exception $e) {
      $response->getBody()->write(json_encode([
        "message" => preg_replace("/input/", "Hasło", $e->getMessage()),
        "field" => "password"
      ]));
      return $response->withStatus(400)->withHeader('Content-type', 'application/json');
    }

    $user = $this->user->read($username);
    $expirationDate = time() + 60*60*24;
    if(!$user || !password_verify($password, $user['passwd'])) {
      $response->getBody()->write(json_encode(['message' => 'błędne dane logowania']));
      return $response->withHeader('Content-type', 'application/json');
    }
    
    $token = $this->generate_token($user['id'], $expirationDate);
    setcookie("user_token", $token, $expirationDate, '/', httponly: true);
    
    $userData = json_encode([
      'username' => $username
    ]);
    setcookie("user_data", $userData, $expirationDate, '/');

    $body = json_encode([
      'message' => 'zalogowano',
      'username' => $username
    ]);
    $response->getBody()->write($body);
    return $response->withHeader('Content-type', 'application/json');
  }

  public function logout(Request $request, Response $response) : Response {
    $expirationDate = time() - 60*60*24;
    setcookie("user_token", "", $expirationDate, '/', httponly: true);
    setcookie("user_data", "", $expirationDate, '/');
    $response->getBody()->write('wylogowano');
    return $response;
  }
}