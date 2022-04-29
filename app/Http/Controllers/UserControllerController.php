<?php

namespace App\Http\Controllers;

use App\Models\UserController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class UserControllerController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->verificador_thoken();
    }

    public function index()
    {
        //
        $this->user = FacadesJWTAuth::parseToken()->authenticate();
        return response()->json(['erro' => false, 'user' =>  $this->user ?? '']);

    }

    public function edit(UserController $userController)
    {
        //
    }

    public function update(Request $request, UserController $userController)
    {
        //
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
