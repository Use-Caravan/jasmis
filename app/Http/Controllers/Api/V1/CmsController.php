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
    Cuisine,
    BranchDeliveryArea,
    DeliveryArea
};
use App\CmsMapping;
use Validator;
use DB;

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
        array_push($cms,['title' => 'FAQ','slug' => 'faq', 'position' => 3, 'description' => '', 'cms_content' => '']);
        $data = [];
        //print_r($cms);exit;
        foreach($cms as $key => $value) {
            //$data[] =  [ 'cms_title' => $value['title'], 'cms_link' => route('frontend.cms', $value['slug']), 'position' => ($value['position'] === null) ? 3 : $value['position']  ];
            $data[] =  [ 'cms_title' => $value['title'], 'cms_link' => route('frontend.cms', $value['slug']), 'cms_description' => $value['description'], 'cms_content' => $value['cms_content'], 'position' => ($value['position'] === null) ? 3 : $value['position']  ];
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

            /** Filter section 2 banners to nearest branch as per customer location **/
            if(request()->latitude !== null &&  request()->longitude !== null) {
                $nearest = $this->getNearestBranches();
                //print_r($nearest);exit;

                $get_section_items = Cms::getList()->where([Cms::tableName().".status" => ITEM_ACTIVE])->where('section',$value['section'])
                    ->leftjoin(CmsMapping::tableName(),Cms::tableName().".cms_id",CmsMapping::tableName().".cms_id")
                    ->whereIn(CmsMapping::tableName().".branch_id", $nearest)
                    ->get()->toArray();

                //$query = $query->whereIn(Branch::tableName().".branch_id", $nearest);
            }
            else {
                $get_section_items = Cms::getList()->where(['status' => ITEM_ACTIVE])->where('section',$value['section'])->get()->toArray();
            }

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

        //$quickbuy_items = ItemResource::collection(Item::getItems()->where('quickbuy_status',ITEM_ACTIVE)->get());

        

        $cuisine = CuisineResource::collection(Cuisine::getList()->where('status',ITEM_ACTIVE)->get());

        //$branches = Branch::getBranches()->where('popular_status',ITEM_ACTIVE)->get();
        //$branches = BranchResource::collection($branches);   

        
        /*$section3 =  [ 
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
                     
                   ];*/

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

        //array_push($data,$section3); 
        //array_push($data,$section4); 
        array_push($data,$section5); 
        array_push($data,$section6);
        //$cms = CmsResource::collection($cms);
        $this->setMessage( __('apimsg.Cms are fetched.') );
        return $this->asJson($data);
    }

    public static function getNearestBranches()
    {
        /** Base Query End */
        $deliveryBranchIDs = [];
        $nearest = [];
        if(request()->latitude !== null &&  request()->longitude !== null) {
            /**
             * Circle contains point reference link
             * https://developers.google.com/maps/solutions/store-locator/clothing-store-locator
             */            
            $deliveryAreasCircle = BranchDeliveryArea::select([
                BranchDeliveryArea::tableName().".branch_id",
                DeliveryArea::tableName().".zone_radius",
                DB::raw(" ( 6371000 * acos( cos( radians(".request()->latitude.") ) * cos( radians( circle_latitude ) )
                * cos( radians( circle_longitude ) - radians(".request()->longitude.") ) + sin( radians(".request()->latitude.") )
                * sin( radians( circle_latitude ) ) ) ) as distance"),
            ])
            ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
            ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",BranchDeliveryArea::tableName().".branch_id")
            ->havingRaw("distance <=  ".DeliveryArea::tableName().".zone_radius")
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_CIRCLE,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE,
            ])
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")
            ->whereNull(DeliveryArea::tableName().".deleted_at")
            ->whereNull(Branch::tableName().".deleted_at")->get();


            if($deliveryAreasCircle !== null) {                
                $deliveryAreasCircle = $deliveryAreasCircle->toArray();
                $deliveryBranchCircle = array_column($deliveryAreasCircle,'branch_id');
                $deliveryBranchIDs = array_merge($deliveryBranchIDs, $deliveryBranchCircle);
            }
            
            /**
             * Polygon contains point reference link
             * https://gis.stackexchange.com/questions/79311/how-to-find-points-inside-each-polygon-in-mysql
             * https://marcgg.com/blog/2017/03/13/mysql-viewport-gis/
             */
            $deliveryAreasPolygon = BranchDeliveryArea::select([
                BranchDeliveryArea::tableName().".branch_id",                
            ])
            ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
            ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",BranchDeliveryArea::tableName().".branch_id")            
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_POLYGON,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE,
            ])
            ->whereNull(DeliveryArea::tableName().".deleted_at")
            ->whereNull(Branch::tableName().".deleted_at")
            ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(".request()->latitude.", ".request()->longitude."))")
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")->get(); 
                        
            if($deliveryAreasPolygon !== null) {
                $deliveryAreasPolygon = $deliveryAreasPolygon->toArray();
                $deliveryBranchPolygons = array_column($deliveryAreasPolygon,'branch_id');
                $deliveryBranchIDs = array_merge($deliveryBranchIDs,$deliveryBranchPolygons);
            }
        }

        if(request()->latitude !== null &&  request()->longitude !== null) {            
            $distance_array = [];
            foreach ($deliveryBranchIDs as $distances) {
                //find distances
                $branch_nearest = Branch::where('branch_id',$distances)->first();

                // $branch_distance = $this->twopoints_on_earth(request()->latitude, request()->longitude, 
                //                      $branch_nearest->latitude,  $branch_nearest->longitude);

                $lat1 = deg2rad(request()->latitude); 
                $lon1 = deg2rad(request()->longitude); 
                $lat2 = deg2rad($branch_nearest->latitude); 
                $lon2 = deg2rad($branch_nearest->longitude); 
                $unit = "K";

                if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                    $distance =  0;
                }
                else {
                    $theta = $lon1 - $lon2;
                    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                    $unit = strtoupper($unit);

                    if ($unit == "K") {
                      $distance = ($miles * 1.609344);
                    } else if ($unit == "N") {
                      $distance = ($miles * 0.8684);
                    } else {
                      $distance = $miles;
                    }
                }

                $branch_arry = ['branch_id' => $distances, 'distance' => $distance,'vendor_id' => $branch_nearest->vendor_id];
                array_push($distance_array, $branch_arry);
            }

            $sort_by_distance = collect($distance_array)->sortBy('distance');
            $unique_vendors = $sort_by_distance->unique('vendor_id');
            $nearest = [];
            foreach ($unique_vendors as $sort) {
                array_push($nearest,$sort['branch_id']);
            }
            // $query = $query->addSelect([
            //         DB::raw(Branch::tableName().".branch_id as branchid"),
            //     ]);

            // $implode = "'" . implode ( "', '", $nearest ) . "'";


            // $query = $query->whereIn(Branch::tableName().".branch_id", $nearest)->orderByRaw("FIELD(branchid, $implode) ASC");
            //$query = $query->whereIn(Branch::tableName().".branch_id", $nearest);
        }

        return $nearest;
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
