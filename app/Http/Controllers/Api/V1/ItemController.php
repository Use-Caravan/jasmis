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
    Vendor,
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
use FileHelper;

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
            if(request()->auto_suggestion == true){
                $suggestions = Item::getItems()->get();
                $items = [];
                foreach ($suggestions as $value) {
                    $set_suggestions = [
                                            
                                            'vendor_id' => $value->vendor_id,
                                            'vendor_key' => Vendor::where('vendor_id',$value->vendor_id)->value('vendor_key'),
                                            'vendor_name' => $value->vendor_name,
                                            'vendor_logo' => FileHelper::loadImage($value->vendor_logo),
                                            'branch_id' => $value->branch_id,
                                            'branch_key' => $value->branch_key,
                                            'branch_name' => $value->branch_name,
                                            'item_id' => $value->item_id,
                                            'item_key' => $value->item_key,
                                            'item_name' => $value->item_name,
                                            'item_image' => FileHelper::loadImage($value->item_image),

                                      ];
                    array_push($items, $set_suggestions);
                }

                $data['items'] = $items;
                $data['vendors'] = collect($data['items'])->unique('vendor_id');
                
                // $data['vendors'] = collect($data['items'])->unique('vendor_id');

            }else{
                $data['items'] = ItemResource::collection(Item::getItems()->get());
                $data['vendors'] = collect($data['items'])->unique('vendor_id');
            }
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
