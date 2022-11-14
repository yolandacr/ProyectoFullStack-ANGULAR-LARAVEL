<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth {

    public $key;

    public function __construct() {
        $this->key = 'esto_es_una_clave_super_secreta-12345';
    }

    /**
     * 
     * @param type $email email del usuario que loguea
     * @param type $password password del usuario que loguea
     * @param type $getToken el usuario identificado, por defecto es null
     * @return string
     */
    public function signup($email, $password, $getToken = null) {
        //Buscar si existe el usuario con sus credenciales
        $user = User::where([
                    'email' => $email,
                    'password' => $password
                ])->first();

        //Comprobar si son correctas (objeto)
        $signup = false;
        if (is_object($user)) {
            $signup = true;
        }

        //Generar el token con los datos del usuario identificado
        if ($signup) {
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'description' =>$user->description,
                'image' => $user->image,
                'iat' => time(), //para meter la fecha de creación
                'exp' => time() + (7 * 24 * 60 * 60)//cuando va acaducar el token
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            //Devolver los datos decodificados o el token en función de un parámetro

            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto'
            );
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false) { //si estuviera en true mandaria la información decodificada.
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {   //existe id usuario (sub)
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }

}
