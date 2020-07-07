<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use FileHelper; 
use App\Http\{
    Controllers\Api\V1\Controller,
    Requests\Admin\CmsRequest,
    Resources\Api\V1\CmsResource,
    Resources\Api\V1\BranchResource,
    Resources\Api\V1\CuisineResource,
    Resources\Api\V1\ItemResource,
    Resources\Api\V1\FaqResource
};
use App\Api\{
    Cms,
    Faq,
    Branch,
    Vendor,
    Item,
    Cuisine
};
use Validator;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cms = Cms::getList()->where(['status' => ITEM_ACTIVE])->where(['section' => NULL])->orderBy('sort_no','asc')->get()->toArray();
        array_push($cms,['title' => 'FAQ','slug' => 'faq', 'position' => 3]);
        $data = [];
        foreach($cms as $key => $value) {
            $data[] =  [ 'cms_title' => $value['title'], 'cms_link' => route('frontend.cms', $value['slug']), 'position' => ($value['position'] === null) ? 3 : $value['position']  ];
        }
        //$cms = CmsResource::collection($cms);
        $this->setMessage( __('apimsg.Cms are fetched.') );
        return $this->asJson($data);
    }

    public function gethomepage()
    {
        $cms = Cms::getList()->where(['status' => ITEM_ACTIVE])->whereIn('section',CMS_SEC)->orderBy('section','asc')->groupBy('section')->get()->toArray();
        $data = [];
        $i = 0;
        foreach($cms as $key => $value) {
            $i++;
            $get_section_items = Cms::getList()->where(['status' => ITEM_ACTIVE])->where('section',$value['section'])->get()->toArray();
            $sectionitems_arr =[];
            $en_banners = [];
            $ar_banners = [];
            foreach ($get_section_items as $key => $sections) {
                $vendor_id = $sections['vendor_id'];
                $as_arabic_banner = $sections['arabic_banner'];
                $images = [
                            'ldpi_image_path'     => FileHelper::loadImage($sections['ldpi_image_path']),
                            'mdpi_image_path'     => FileHelper::loadImage($sections['mdpi_image_path']),
                            'hdpi_image_path'     => FileHelper::loadImage($sections['hdpi_image_path']),
                            'xhdpi_image_path'    => FileHelper::loadImage($sections['xhdpi_image_path']),
                            'xxhdpi_image_path'   => FileHelper::loadImage($sections['xxhdpi_image_path']),
                            'xxxhdpi_image_path'  => FileHelper::loadImage($sections['xxxhdpi_image_path']),                          
                          ];
                $section_items = [

                                    'vendor_key' => Vendor::where('vendor_id',$sections['vendor_id'])->value('vendor_key'),
                                    'vendor_id' => $sections['vendor_id'],
                                    'as_arabic_banner' => $sections['arabic_banner'],
                                    'branch_key' => Branch::where('branch_id',$sections['branch_id'])->value('branch_key'),
                                    'branch_id' => $sections['branch_id'],
                                    'item_id' => null,
                                    'item_name' => '',
                                    'restarunt_name' => '',
                                    'item_price' => '',
                                    'image_link' => '',
                                    'image' => $images,
                                 ];

                array_push($sectionitems_arr, $section_items);
            }
            
            foreach($sectionitems_arr as $avalue )
            {
                if($avalue['vendor_id']!= '')
                {
                if($avalue['as_arabic_banner'] == 1)
                {
                       array_push($ar_banners, $avalue);
                }
                else
                {
                        array_push($en_banners, $avalue);
                }  
                }
            }

            
                       
            $data[] =  [ 
                         'section_id' => $value['section'], 
                         'section_name' => $value['title'], 
                         'no_of_items' => '', 
                         'section_items' => $sectionitems_arr,
                         'section_items_'.$i => $sectionitems_arr,
                         'en_banners'  => $en_banners,
                         'ar_banners' => $ar_banners

                       ];


         
        }

        $quickbuy_items = ItemResource::collection(Item::getItems()->where('quickbuy_status',ITEM_ACTIVE)->get());

        

        $cuisine = CuisineResource::collection(Cuisine::getList()->where('status',ITEM_ACTIVE)->get());

        $branches = Branch::getBranches()->where('popular_status',ITEM_ACTIVE)->get();
        $branches = BranchResource::collection($branches);   

        
        $section3 =  [ 
                     'section_id' => 3, 
                     'section_name' => 'Quick Buy', 
                     'no_of_items' => '', 
                     'section_items' => $quickbuy_items,
                     'section_items_3' => $quickbuy_items
                       

                   ];

        $section4 =  [ 
                     'section_id' => 4, 
                     'section_name' => 'Popular Brands', 
                     'no_of_items' => '', 
                     'branches' => $branches
                     
                   ];

         $section5 =  [ 
                     'section_id' => 5, 
                     'section_name' => 'Sort & Filter', 
                     'no_of_items' => '', 
                     'cuisine' => $cuisine
                     
                       

                   ];

        $section6 =  [ 
                     'section_id' => 6, 
                     'section_name' => 'Restaurants', 
                     'no_of_items' => '', 
                     'branches' => BranchResource::collection(Branch::getBranches()->get()),
                     'section_items_6' => BranchResource::collection(Branch::getBranches()->get())
                       

                   ];

        array_push($data,$section3); 
        array_push($data,$section4); 
        array_push($data,$section5); 
        array_push($data,$section6);
        //$cms = CmsResource::collection($cms);
        $this->setMessage( __('apimsg.Cms are fetched.') );
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
        //
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
