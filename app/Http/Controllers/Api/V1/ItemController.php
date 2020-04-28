<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\ItemResource,
    Resources\Api\V1\CategoryResource
};
use Illuminate\Http\Response;
use App\Api\{
    Item,
    ItemGroupMapping,
    IngredientGroupMapping,
    IngredientLang,
    IngredientGroupLang,
    IngredientGroup,
    Branch,
    Category
};
use Validator;
use DB;
use App;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(request()->branch_key != null && request()->webfilter === null) {
            $data = CategoryResource::collection(Category::getCategories()->get());
        } else {
            $data = ItemResource::collection(Item::getItems()->get());
        }
        $this->setMessage( __('apimsg.Items are fetched.') );            
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $data = Item::getItems($id)->first();
        $this->setMessage( __('apimsg.Items are fetched.') );
        if($data === null) {
            $data = [];
            $this->setMessage( __('apimsg.No records found.') );  
        } else {            
            $data = new ItemResource($data);
        }
        return $this->asJson($data);
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
