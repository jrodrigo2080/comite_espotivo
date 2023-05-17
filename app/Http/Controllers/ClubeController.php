<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\Models\Clube;
use App\Models\Recurso;
use Illuminate\Http\Request;

class ClubeController extends Controller
{
    public function index(){
        $dados = Clube::select('id','clube', 'saldo_disponivel')->get();
        return response()->json($dados);
    }

    public function create(Request $request){
        try {
            $validatedData = $request->validate([
                'clube' => 'required|string|max:50|unique:clubes',
                'saldo_disponivel' => 'required',
            ],[
                'clube.required' => 'O campo clube é obrigatório.',
                'clube.unique' => 'Clube já cadastrado com esse nome',
                'saldo_disponivel.required' => 'O campo saldo_disponivel é obrigatório.',
            ]);

            if($validatedData){
                $data = [
                    "status"=>"error",
                    "message"=>$validatedData
                ];
                return response()->json($data, 400);
            }

            $clube = new Clube();

            $clube->clube = $request->clube;
            $clube->saldo_disponivel = $request->saldo_disponivel;
            if($clube->save()){
                $data = [
                    "status"=>"success",
                    "message"=>"clube cadastrado com sucesso!"
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

    public function consumirRecurso(Request $request){
         //validar se exite recurso e clube
        $clube = Clube::where('id',$request->clube_id)->get();
        if($clube->count() == 0){
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => 'clube não encontrado',
            ], 422);
        }

        $recurso = Recurso::where('id',$request->recurso_id)->get();
        if($recurso->count() == 0){
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => 'recurso não encontrado',
            ], 422);
        }

        //verificar se o saldo do clube esta negativo e valida se existe saldo suficiente no clube
        if($clube[0]->saldo_disponivel < 0 || $clube[0]->saldo_disponivel < $request->valor_consumo){
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => 'o clube não tem saldo suficiente para este recurso!',
            ], 422);
        }

        $dados_clube = [
            'saldo_disponivel' => $clube[0]->saldo_disponivel - number_format(floatval($request->valor_consumo), 2)
        ];
        Clube::where('id', $request->clube_id)->update($dados_clube);

        $dados_recurso = [
            'saldo_disponivel' => $recurso[0]->saldo_disponivel - number_format(floatval($request->valor_consumo), 2)
        ];
        Recurso::where('id', $request->recurso_id)->update($dados_recurso);

        $dados_retorno =[
            "clube"=> $clube[0]->clube,
            "saldo_anterior"=> $clube[0]->saldo_disponivel,
            "saldo_atual"=> $clube[0]->saldo_disponivel - number_format(floatval($request->valor_consumo), 2)
        ];
        return response()->json($dados_retorno);

    }
}
