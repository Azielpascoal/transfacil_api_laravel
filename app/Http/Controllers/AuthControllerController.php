<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthControllerController extends Controller
{

    public function authenticate(Request $request)
    {
      $user= $this->verificador_thoken();
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|string|max:255',
            'password' => 'required|string|unique:users',
        ]);
        if ($validator->fails()) {
            return response()->json(['erro' => true, 'status' => 'Erro de dados', $validator->errors()->toJson(), 400]);
        }

        try {
            if (!$token = FacadesJWTAuth::attempt($credentials)) {
                return response()->json(['error' => true, 'status' => 'Dados incorretos'], 400);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Não foi possível criar token'], 500);
        }

        return response()->json(['erro' => false,'status' => 'certo', 'token' => $token], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_cliente' => 'required|string',
            'email' => 'required|email',
            'n_bilhete' => 'required|string',
            'telefone' => 'required|string',
            'password' => 'required|string',
            'tipo_de_conta' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['erro' => true, 'status' => 'Erro', $validator->errors()->toJson(), 400]);
        }

        switch ($request->tipo_de_conta) {
            case 'cliente':
                $validator = Validator::make($request->all(), [
                    'nome_cliente' => 'required|string',
                    'email' => 'required|email',
                    'n_bilhete' => 'required|string',
                    'telefone' => 'required|string',
                    'password' => 'required|string',
                    'tipo_de_conta' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return response()->json(['erro' => true, 'status' => 'Dados incorretos', $validator->errors()->toJson(), 400]);
                }
                break;
            case 'taxista':
                $validator = Validator::make($request->all(), [
                    'nome_cliente' => 'required|string',
                    'email' => 'required|email',
                    'n_bilhete' => 'required|string',
                    'carta_de_conducao' => 'required|string',
                    'discricao_do_carro' => 'required|string',
                    'rota_inical' => 'required|string',
                    'password' => 'required|string',
                    'tipo_de_conta' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return response()->json(['erro' => true, 'status' => 'Dados incorretos', $validator->errors()->toJson(), 400]);
                }
                //findRouteName()
                break;
            default:
                return response()->json(['erro' => true, 'status' => 'Dados invalidos', 400]);
        }
        try {
            // validar routa

            $user = User::create([
                'nome_cliente' => $request->nome_cliente,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'n_bilhete' => $request->n_bilhete,
                'carta_de_conducao' => $request->carta_de_conducao ?? '',
                'discricao_do_carro' => $request->discricao_do_carro ?? '',
                'tipo_de_conta' => $request->tipo_de_conta,
                'password' => Hash::make($request->password),
            ]);

            $token = FacadesJWTAuth::fromUser($user);

            if (!empty($user)) {
                return response()->json(['erro' => false, 'user' => $user, 'token' => $token ?? '', 'status' => 'Registro feito com sucesso', 201]);
            } else {
                return response()->json(['erro' => true, 'status' => 'ups acção não esperada 😥', 401]);
            }
        } catch (QueryException $e) {
            return response()->json(['erro' => true, 'status' => 'Não cadastrado', 'mensagem' => 'duplicação de valores', 400]);
        }
    }


    private function  verificador_thoken()
    {
        try {
            if (!$user = FacadesJWTAuth::parseToken()->authenticate()) {
                return response()->json(['usuário não encontrado'], 404);
            }
            return $user;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token expirado'], 404);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token inválido'], 404);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token ausente'], 404);
        }
    }
}
