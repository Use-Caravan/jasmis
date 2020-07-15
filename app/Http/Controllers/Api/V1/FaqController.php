<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use Illuminate\Http\Response;
use App\Api\Faq;
use Validator;


class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                
        //echo 'faq';exit;
        $query = Faq::getList();        
        if($request->limit != '') {
            $query = $query->limit($request->limit);
        }
        $query = $query->get();
        $data = FaqResource::collection($query);
        $this->setMessage( __('apimsg.Cuisines are fetched.') );
        return $this->asJson($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                
        $validator = Validator::make($request->all(),
            [
                'cuisine_name.*' => 'required',
                'sort_no' => "numeric|nullable"
            ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }        
        return $this->asJson($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Cuisine::getList()->where(['cuisine.cuisine_id' => $id])->first();

        return (new CuisineResource($data))
        ->additional(['meta' => [
            'status' => 200,
            'time' => time(),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }    
}