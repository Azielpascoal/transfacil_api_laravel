<?php

namespace App\Http\Controllers;

use App\Models\RotasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class RotasControllerController extends Controller
{

    private $user;
    public function __construct()
    {
        $this->verificador_thoken();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RotasController $rotasController)
    {
        //
        $this->verificador_thoken();
        $validator = Validator::make($request->all(), [
            'nome_rota' => 'required|string|unique:rotas_controllers,nome_rota',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['erro' => true, 'status' => 'Dados incorretos', $validator->errors()->toJson(), 400]);
        }
        if ($rotasController->createRoute($request->nome_rota, $request->latitude, $request->longitude)) {
            return response()->json(['erro' => false, 'status' => 'Rota criada 😀', 201]);
        };
        return response()->json(['erro' => true, 'status' => 'Ups não foi possvel cria a rotas😀', 400]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RotasController  $rotasController
     * @return \Illuminate\Http\Response
     */
    public function show(RotasController $rotasController)
    {
        //
        $rotas = $rotasController->getrotasAll();
        return response()->json(['erro' => false, 'rotas' => $rotas ?? '']);
    }


    public function edit(RotasController $rotasController)
    {
        //
    }


    public function update(Request $request, RotasController $rotasController)
    {
        //
    }

    public function destroy(RotasController $rotasController)
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
