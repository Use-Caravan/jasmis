<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Helpers\Curl;
use App\Http\{
    Controllers\Controller\Admin,
    Requests\Admin\DeliveryboyRequest
};
use App\{
    Mail\DeliveryboyEmail,
    CModel,    
    Deliveryboy,
    DeliveryboyLang
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;
use Hash;
use Illuminate\Http\Response;


/**
 * @Title("Delivery boy Management")
 */
class DeliveryboyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title("List")
     */
    public function index(Request $request)
    {                                       
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver/company?company_id=".config('webconfig.company_id');
        $response = new Curl();
        $response->setUrl($url);        
        $data = $response->send();
        $response = json_decode($data,true);
        $drivers = [];
        if($response['status'] === HTTP_SUCCESS) {
            $drivers = $response['data'];
        }
        $deliveryboy = new Deliveryboy();
        return view('admin.deliveryboy.index',compact('drivers','deliveryboy') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title("Create")
     */
    public function create(Request $request)
    {        
        $model = new Deliveryboy();
        $modelLang = new DeliveryboyLang();         
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.deliveryboy.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryboyRequest $request)
    {                
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver?company_id=".config('webconfig.company_id');
        // $data = ['name' => $request->name];
        
        $post_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'password' => $request->password,
            'is_approved' => "true"
        ];
        $model = new Deliveryboy();
        $response = new Curl();
        $response->action('POST');
        $response->setUrl($url);
        $response->setContentType('multipart/form-data');
        $data = $response->send($post_data); 
        
        $response = json_decode($data,true);   
        if($response['status'] == HTTP_SUCCESS) {                
            /* $mailData = [
                'userName' => $model->username,
                'email' => $model->email,
                'mobileNumber' => $model->mobile_number,
                'password' => $request->password,
            ];
            Mail::to($model->email)->send(new DeliveryboyEmail($mailData)); */
            Common::log("Create","Delivery boy has been saved",$model);                
            return redirect()->route('deliveryboy.index')->with('success', $response['message'] );
        } else {            
            //return redirect()->route('deliveryboy.create')->with('error', $response['message'])->withInput();
            return redirect()->route('deliveryboy.index')->with('success', $response['message'] );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {            
        $model = Deliveryboy::showDeliveryboy($id);
        return view('admin.deliveryboy.show',compact('model'));
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
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver/$id?company_id=".config('webconfig.company_id');
        $response = new Curl();
        $response->setUrl($url);        
        $data = $response->send();
        $response = json_decode($data,true);        
        $model = new Deliveryboy();
        if($response['status'] === HTTP_SUCCESS) {            
            $model = $model->fill($response['data']);
            $model->address = $response['data']['address'];
            $model->_id = $response['data']['_id'];
        }        
        return view('admin.deliveryboy.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,DeliveryboyRequest $request)
    {        
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver?company_id=".config('webconfig.company_id')."&driver_id=$id";
        $post_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            //'password' => $request->password,
            'is_approved' => "true",
        ];
        $model = new Deliveryboy(); 
        $response = new Curl();
        $response->action('POST');
        $response->setUrl($url);
        $response->setContentType('multipart/form-data');
        $data = $response->send($post_data);        

        $response = json_decode($data,true);        
        
        if($response['status'] == HTTP_SUCCESS) {
            Common::log("Create","Delivery boy has been updated",$model);                
            return redirect()->route('deliveryboy.index')->with('success', $response['message'] );
        } else {            
            return redirect()->route('deliveryboy.edit',['deliveryboy' => $id])->with('error', $response['message'])->withInput();
        }
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
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver/delete/$id?company_id=".config('webconfig.company_id');
        $model = new Deliveryboy();
        $response = new Curl();
        $response->action('DELETE');
        $response->setUrl($url);            
        $data = $response->send();
        $response = json_decode($data,true);
        if($response['status'] == HTTP_SUCCESS) {        
            Common::log("Destroy","Deliveryboy has been deleted",new Deliveryboy);
            return redirect()->route('deliveryboy.index')->with('success', __('admincrud.Delivery boy deleted successfully'));
        } else {
            return redirect()->route('deliveryboy.index')->with('error', __('admincrud.Something went wrong'));
        }
    }

    /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    { 
        if($request->ajax()){

            $driverId = $request->itemkey;
            $status = $request->status;
            $url = config('webconfig.deliveryboy_url')."/api/v1/driver/$driverId/status/$status?company_id=".config('webconfig.company_id');
            $model = new Deliveryboy();
            $response = new Curl();
            $response->action('PUT');
            $response->setUrl($url);            
            $data = $response->send();
            $response = json_decode($data,true);
            if($response['status'] == HTTP_SUCCESS) {
                $result = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Delivery boy status updated successfully') ];                
            } else {
                $result = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            }                                                         
            Common::log("Status Update","Delivery boy status has been changed",new Deliveryboy());
            return response()->json($result);
        }
    }

     /**
     * Change the approved status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function approvedStatus(Request $request)
    {  
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Deliveryboy::findByKey($request->itemkey);
            $model->approved_status = $request->approved_status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Delivery boy approved status updated successfully') ];
            }            
            Common::log("Approved Status Update","Delivery boy approved status has been changed",$model);
            return response()->json($response);
        }
    }


    public function trackDeliveryboy()
    {
        return view('admin.deliveryboy.track-drivers');
    }
}
