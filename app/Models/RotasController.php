<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RotasController extends Model
{
    use HasFactory;


    public function getrotasAll(){
        $query = "select * from rotas_controllers ";
        return $result = DB::connection()->select($query, [
        ]);
    }
    /**
     * createRoute
     *
     * @param String $nome_rota
     * @param String $latitude
     * @param String $longitude
     * @return bool
     */
    public function createRoute($nome_rota,$latitude,$longitude):bool{
        $query = "insert into rotas_controllers(nome_rota,latitude,longitude)values(?,?,?)";
        return $createRoute= DB::insert($query, [
            $nome_rota,
            $latitude,
            $longitude
        ]);
    }
    /**
     * findRouteName
     *
     * @param String $nome_rota
     * @return array
     */
    public function findRouteName($nome_rota):array{
        $query = "select * from rotas_controllers where nome_rota=:name_rota";
        return $createRoute= DB::select($query, [
            $nome_rota
        ]);
    }
}
