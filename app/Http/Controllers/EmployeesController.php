<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator as ValidationValidator;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page=10;
        if($request->per_page){
            $per_page = $request->per_page;
        }
        //
        $data = Employees::paginate($per_page);
        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'first_name'=> 'required',
            'last_name'=> 'required',
            'company_id'=>'required'
        ];
        $validator = FacadesValidator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $data = Employees::create($request->all());
        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data = Employees::find($id);
        if(!$data){
            return response()->json(['message'=>'Not Found!'],404);
        }
        return response()->json($data,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = Employees::find($id);
        if(!$data){
            return response()->json(['message'=>'Not Found!'],404);
        }
        $rules = [
            'first_name'=> 'required',
            'last_name'=> 'required',
            'company_id'=>'required'
        ];
        $validator = FacadesValidator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $data->update($request->all());
        return response()->json($data,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data= Employees::find($id);
        if(!$data){
            return response()->json(['message'=>'Not Found!'],404);
        }
        $data->delete();
        return response()->json(null,204);
    }
}
