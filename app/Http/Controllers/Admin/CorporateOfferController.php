<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\CorporateOfferRequest
};
use App\{
    CorporateOffer,
    CorporateOfferLang
};
use Auth;
use Session;
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Offer Management")
 */
class CorporateOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {
        $model = new CorporateOffer;
        if($request->ajax()) {
            $model = CorporateOffer::getList();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('offer_type', function ($model) {
                                return $model->offerType($model->offer_type);
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'corporate-offer.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-offer.show',
                                    [ 'id' => $model->corporate_offer_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-offer.edit',
                                    [ 'id' => $model->corporate_offer_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-offer.destroy',
                                    [ 'id' => $model->corporate_offer_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.corporate-offer.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {        
        $model = new CorporateOffer(); 
        $modelLang = new CorporateOfferLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.corporate-offer.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CorporateOfferRequest $request)
    {   
        DB::beginTransaction();
        try {
            $model = new CorporateOffer();
            $model->fill($request->all());
            $model->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_datetime));
            $model->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_datetime));
            $model->save();            
            Common::log("Create","Corporate offer has been created",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('corporate-offer.index')->with('success', __('admincrud.Corporate offer added successfully') );
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
     * @Title("Edit")
     */
    public function edit($id,Request $request)
    {       
        $model = CorporateOffer::findByKey($id);
        $modelLang = CorporateOfferLang::loadTranslation(new CorporateOfferLang,$model->corporate_offer_id);
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.corporate-offer.update', compact('model','modelLang'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CorporateOfferRequest $request, $id)
    {        
        DB::beginTransaction();
        try {
            $model = CorporateOffer::findByKey($id);            
            $model->fill($request->all());
            $model->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_datetime));
            $model->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_datetime));
            $model->save();            
            Common::log("Create","Corporate offer has been updated",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('corporate-offer.index')->with('success', __('admincrud.Corporate offer updated successfully') );
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Title("Delete")
     */
    public function destroy($id)
    {
        $model = CorporateOffer::findByKey($id)->delete();
        Common::log("Destroy","Offer has been deleted",new CorporateOffer());
        return redirect()->route('corporate-offer.index')->with('success', __('admincrud.Corporate offer deleted successfully') );
    }

    
    /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = CorporateOffer::findByKey($request->itemkey);            
            $model->status = $request->status;           
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Corporatre offer status updated successfully')];
            }            
            Common::log("Status Update","Corporate offer status has been changed",$model);
            return response()->json($response);
        }
    }
}
