<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth
{
  public $key;
  
  public function __construct()
  {
    $this->key = 'api_servicios_cca_esign-20210105';
  }

  public function signup($email, $password, $getToken = null)
  {
    # Buscar si existe el usuario con sus contraseñas
    $user = User::where([
      'email' => $email,
      'password' => $password
    ])->first();

    # Comprobar si son correctas (objeto)
    $signup = false;

    if(is_object($user)){
      $signup = true;
    }
    
    # Generar el token con los datos del usuario identificado
    if($signup){
      $token = array(
        'sub'   => $user->id,
        'email' => $user->email,
        'name'  => $user->name,
        'iat'   => time(),
        // 'exp'   => time() + (60) // dura un minuto
        'exp'   => time() + (9*60*60) // dura 9 horas (testing)
        //'exp'   => time() + (7 * 24 * 60 * 60) // dura una semana
      );

      $jwt = JWT::encode($token, $this->key, 'HS256');
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);

      # Devolver los datos decodificados o el token, en función de un parámetro
      if(is_null($getToken)){
        $data = $jwt;
      }else{
        $data = $decoded;
      }

    }else{
      $data = array(
        'status'=>  'error',
        'msg'   => 'Login incorrecto.'
      );
    }
    
    return $data;
  }

  public function checkToken($jwt, $getIdentity = false)
  {
    $auth = false;

    try{
      $jwt = str_replace('Bearer', '', $jwt);
      $jwt = str_replace(' ', '', $jwt);
      $jwt = str_replace('"', '', $jwt);
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);
    }catch(\UnexpectedValueException $e){
      $auth = false;
    }catch(\DomainException $e){
      $auth = false;
    }
    
    if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
      $auth = true;
    }else{
      $auth = false;
    }

    if($getIdentity){
      return $decoded;
    }

    return $auth;
  }

}