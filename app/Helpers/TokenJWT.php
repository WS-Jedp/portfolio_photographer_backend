<?php

namespace Helpers;


class TokenJWT {


  private $secret;

  public function __construct()
  {
    $this->secret = $_SERVER["SECRET"];
  }

  public function create($data)
  {
    $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
    ]);
    $payload = json_encode($data);

    // Encode
    $base64UrlHeader = $this->base64UrlEncode($header);
    $base64UrlPayload = $this->base64UrlEncode($payload);

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = $this->base64UrlEncode($signature);

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
  }


  public function update(){}
  
  public function delete($token)
  {

    $expired = $this->verify($token);
    if($expired) {
      throw new \Exception("Token is expired!");
    }

    $token_parts = explode(".", $token);
    $header = base64_decode($token_parts[0]);
    $payload = base64_decode($token_parts[1]);
    $signature = $token_parts[2];

    $new_payload = json_decode($payload);
    $new_payload->exp = 0;

    $headerEncoded = base64_encode($header);
    $payloadEncoded = base64_encode($payload);
    $new_signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secret, true);

    $newToken = $this->base64UrlEncode($new_signature);

    return $newToken;
  }

  public function verify($token)
  {
    $token_parts = explode(".", $token);
    $header = base64_decode($token_parts[0]);
    $payload = base64_decode($token_parts[1]);
    $signature = $token_parts[2];

    $tokenTime = json_decode($payload)->exp;

    $expiration = $this->isTokenExpired($tokenTime);

    if($expiration) {
      throw new \Exception("Token is expired!");
    }

    $headerEncoded = base64_encode($header);
    $payloadEncoded = base64_encode($payload);
    $signature_to_verify = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, $this->secret, true);

    $signatureToVerify = $this->base64UrlEncode($signature_to_verify);

    if($signature === $signatureToVerify) {
      return true;
    } else {
      return false;
    }


  }

  private function base64UrlEncode($text)
  {
    return str_replace(
      ['+', '/', '='],
      ['-', '_', ''],
      base64_encode($text)
    );
  }

  private function isTokenExpired($expireTime = 0) {
      $now = (new \DateTime('now'))->getTimestamp();
      return ($expireTime - $now < 0);
  }
}