<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseController {
  public static function Autorize() : String | null {
    if(isset($_COOKIE['user_token'])){
      $token = $_COOKIE['user_token'];
      try{
        $decoded = (array) JWT::decode($token, new Key($_ENV['JWT_KEY'], $_ENV['JWT_ALG']));
        return $decoded['username'];
      } catch(\Exception $e) {
        return null;
      }
    } else return null;
  }

  public static function Validate(string $input, int $minLength = 0, int $maxLength = 100, bool $limitCharset = false) : String {
    //$input = htmlspecialchars($input);
    if(strlen($input) < $minLength) throw new \Exception("input musi zawierać przynajmniej {$minLength} znaków");
    if(strlen($input) > $maxLength) throw new \Exception("input może zawierać maksymalnie {$maxLength} znaków");
    if($limitCharset && !preg_match("/^[_a-zA-Z0-9]*$/", $input)) throw new \Exception("input zawiera niedozwolone znaki");
    return $input;
  }
}