<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public function test(Request $request)
    {
        return "Test de USER-CONTROLLER";
    }

    public function register(Request $request)
    {
        # Obtener los datos del usuario
        $json = $request->input('json', null);
        $params = json_decode($json); // devuelve un objeto
        $params_array = json_decode($json, true); // devuelve un array

        if(!empty($params) && !empty($params_array)){
            # Limpiar datos 
            $params_array = array_map('trim', $params_array);
            
            # Reglas de validación
            $reglas = array(
                'name' => 'required',
                'email' => 'required|email|unique:users', # Comprueba si existe el usuario (evitar duplicados)
                'password' => 'required'
            );
            
            # Validar datos 
            $validate = Validator::make($params_array, $reglas);

            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'msg' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            }else{
                # Validación pasada correctamente
                
                # Cifrar contraseña
                $pwd = hash('sha256',$params->password);

                # Crear usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->email = $params_array['email'];
                $user->password = $pwd;

                # Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'msg' => 'Usuario creado correctamente',
                    'user' => $user
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'msg' => 'Los datos enviados no son correctos'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        # Recibir datos por Post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        # Validar datos
        $validate = \Validator::make($params_array, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validate->fails()){
            # validacion fallida
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'msg' => 'El usuario no se pudo identificar',
                'errors' => $validate->errors()
            );
        }else{
            # Cifrar password
            $pwd = hash('sha256',$params->password);

            # Devolver token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);

            if(!empty($params->getToken)){
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }
        
        return response()->json($signup, 200);
    }

    public function update(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        # Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if($checkToken && !empty($params_array)){
            # Actualizar usuario
            # Obtener usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            # validar los datos 
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'email' => 'required|email|unique:users,'.$user->sub
            ]);

            # Quitar campos que no se actualizaran
            unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            # Actualizar usuario en BBDD
            $user_update = User::where('id', $user->sub)->update($params_array);

            # Devolver array con resultado
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
            );

        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'msg' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }
}
