<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller {

    public function pruebas(Request $request) {
        return "Acción de pruebas e UserController";
    }

    public function register(Request $request) {
        /* $name = $request->input('name');
          $surname = $request->input('surname');
          return "Acción de registro de usuarios:: $name $surname"; */

        //Recoger los datos del usuario por post
        $json = $request->input('json', null);

        //Decodificar datos
        $params = json_decode($json); //esta funcion json_decode decodifica convirtiendo en un objeto php.
        $params_array = json_decode($json, true); //decodifica sacando los datos en un array.

        if (!empty($params) && !empty($params_array)) {
            //Limpiar datos
            $params_array = array_map('trim', $params_array);

            //Validar datos
            //\Validator es igual que poner use arriba e importarlo, es un alias.
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users', //Comprobar si el usuario existe ya (duplicado)
                        'password' => 'required',
            ]);

            if ($validate->fails()) {
                //La validación ha fallado

                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            } else {
                //Validación pasada correctamente
                //Cifrar la contraseña
                $pwd = hash('sha256', $params->password);

                //Crear el usuario
                $user = New User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                //Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos',
            );
        }
        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        $jwtAuth = new \JwtAuth();

        //Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true); //parametro true para que decodifique ese json en un array.
        //Validar esos datos
        $validate = \Validator::make($params_array, [
                    'email' => 'required|email',
                    'password' => 'required'
        ]);

        if ($validate->fails()) {
            //La validación ha fallado

            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podio identificar',
                'errors' => $validate->errors()
            );
        } else {

            //Cifrar contraseña
            $pwd = hash('sha256', $params->password);

            //Devolver token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);
            if (!empty($params->gettoken)) {  //si existe el parametro gettoken y no está vacío
                $signup = $jwtAuth->signup($params->email, $pwd, true); //true para devolver los datos decodificados
            }
        }
        // este método funciona en versiones más modernas de laravel  solo
        //return $jwtAuth->signup($email,$pwd,true);
        return response()->json($signup, 200);
    }

    /**
     * método para actualizar los datos del usuario.Comprobar token.
     */
    public function update(Request $request) {

        //Comprobar si el usuario está identificado
        $token = $request->header('Authorization'); //para pillar el token de los datos
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            //Sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            //Validar los datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users,' . $user->sub
            ]);

            //Quitar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            //Actualizar datos del usuario en la base de datos
            $user_update = User::where('id', $user->sub)->update($params_array);

            //Devolver un array con el resultado
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
            );
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no está identificado.'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function upload(Request $request) {

        //Recoger datos de la petición (el fichero en sí, etc)
        $image = $request->file('file0');

        //Validar imagen
        $validate = \Validator::make($request->all(), [
                    'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //Guardar imagen
        if (!$image || $validate->fails()) {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen.'
            );
        } else {
            $image_name = time() . $image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {
        $isset = \Storage::disk('users')->exists($filename);

        if ($isset) {
            $file = \Storage::disk('users')->get($filename);

            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe.'
            );
            return response()->json($data, $data['code']);
        }
    }

    public function detail($id) {
        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe.'
            );
        }
        
        return response()->json($data,$data['code']);
    }

}
