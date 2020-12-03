<?php

namespace App\Http\Controllers;

use App\Models\companies;
use App\Models\Companies as ModelsCompanies;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator;


class CompaniesController extends Controller
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
        $data = companies::paginate($per_page);
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
            'name'=> 'required',
            'logo'=>'dimensions:min_width=100,min_height=100'
        ];
        $validator = FacadesValidator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $fileName = date('dmY_His') . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('local')->put('public/'.$fileName,  file_get_contents($file));
            $request->logo = $fileName;
        }
        $companies = ModelsCompanies::create($request->all());
        return response()->json(null,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $companies = ModelsCompanies::find($id);
        if(!$companies){
            return response()->json(['message'=>'Not Found!'],404);
        }
        return response()->json($companies,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  id  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $companies = ModelsCompanies::find($id);
        if(!$companies){
            return response()->json(['message'=>'Not Found!'],404);
        }
        $rules = [
            'name'=> 'required',
            'logo'=>'dimensions:min_width=100,min_height=100'
        ];
        $validator = FacadesValidator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $fileName = date('dmY_His') . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('local')->put('public/'.$fileName,  file_get_contents($file));
            $request->logo = $fileName;
        }
        $companies->update($request->all());
        return response()->json($companies,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\companies  $companies
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $companies= ModelsCompanies::find($id);
        if(!$companies){
            return response()->json(['message'=>'Not Found!'],404);
        }
        $companies->delete();
        return response()->json(null,204);
    }

    public function comboData()
    {
        # code...
        $companies = ModelsCompanies::select('id','name')->get();
        return response()->json($companies,200);
    }
}
