<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index(){
        $dados = Recurso::select('id','recurso', 'saldo_disponivel')->get();
        return response()->json($dados);
    }

    public function create(Request $request){
        try {
            $validatedData = $request->validate([
                'recurso' => 'required|string|max:250|unique:recursos',
                'saldo_disponivel' => 'required',
            ],[
                'recurso.required' => 'O campo recurso é obrigatório.',
                'recurso.unique' => 'recurso já cadastrado com esse nome',
                'saldo_disponivel.required' => 'O campo saldo_disponivel é obrigatório.',
            ]);

            $recurso = new Recurso();

            $recurso->recurso = $request->recurso;
            $recurso->saldo_disponivel = $request->saldo_disponivel;
            if($recurso->save()){
                $data = [
                    "status"=>"success",
                    "message"=>"recurso cadastrado com sucesso!"
                ];
                return response()->json($data, 201);
            }
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors();

            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $errors,
            ], 422);
        }

    }

}
