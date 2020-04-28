<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\NewsletterSubscriberRequest    
};
use Maatwebsite\Excel\Excel;
use DataTables;
use App\{
    Mail\NewsletterSendEmail,
    Exports\NewsletterExport,    
    NewsletterSubscriber,
    Newsletter,
    EmailQueue
};
use Maatwebsite\Excel\Exporter;
use Validator;
use Common;
use DB;
use HtmlRender;
use Html;
use Mail;

/**
 * @Title("Newsletter Subscriber Management")
 */
class NewsletterSubscriberController extends Controller
{
   
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {                
        if($request->ajax()) {
            $model = NewsletterSubscriber::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'newsletter-subscriber.status');
                            })
                        ->addColumn('action', function ($model) {
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter-subscriber.show',
                                    [ 'id' => $model->newsletter_subscriber_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter-subscriber.edit',
                                    [ 'id' => $model->newsletter_subscriber_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter-subscriber.destroy',
                                    [ 'id' => $model->newsletter_subscriber_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    );
                                return "$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.newsletter-subscriber.index');
    }

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
       //echo $id;
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\ttp\Response
     * @Title("Delete")
     */
    public function destroy($id)
    { 
        $model = NewsletterSubscriber::findByKey($id)->delete();
        Common::log("Destroy","Newlettersubscriber has been deleted",new NewsletterSubscriber());
        return redirect()->route('newsletter-subscriber.index')->with('success', __('admincrud.Subscriber deleted successfully'));
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
            $model = NewsletterSubscriber::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Subscriber status updated successfully')];
            }
            Common::log("Newlettersubscriber Status","Newlettersubscriber status has been changed",$model);
            return response()->json($response);
        }
    }
    
    /**
     * @Title("Send Newsletter")
     */
    public function sendMail(Request $request)
    {   
        if($request->method() == 'GET') {
            $model = new NewsletterSubscriber();
            $subscriberEmails = NewsletterSubscriber::selectSubscriberEMail(); 
            $newsletters = Newsletter::selectNewsletters();
            return view('admin.newsletter-subscriber.mailsendcreate',compact('model','subscriberEmails','newsletters'));
        }
        if($request->method() == 'POST') {   
            Validator::make($request->all(), [
                'newsletter_subscribers' => 'required',
                'newsletter_id' => 'required',
            ])->validate();                     
           $newsletter =  Newsletter::find($request->newsletter_id);
           $newsletterSubscriber = NewsletterSubscriber::whereIn('newsletter_subscriber_id',$request->newsletter_subscribers)->get()->toArray();
           $details = [];           
           foreach($newsletterSubscriber as $key => $value) {                           
                $details['newsletter_title'] = $newsletter->newsletter_title;
                $details['newsletter_content'] = $newsletter->newsletter_content;                
                Mail::to($value['email'])->send(new NewsletterSendEmail($details));
                $emailQueue = new EmailQueue();
                $emailQueueDetails = ['to_address' => $value['email'],'subject' => $newsletter->newsletter_title, 'content' => $newsletter->newsletter_content];
                $emailQueue = $emailQueue->fill($emailQueueDetails);
                $emailQueue->status = 1;
                $emailQueue->save();
             }
            Common::log("Newsletter Send","Newsletter has been sent to subscribers",new NewsletterSubscriber());
            return redirect()->route('newsletter-subscriber.index')->with('success',__('admincrud.Newslettter has been sent')); 
        }        
    }

    public function newsletterExport()
    {
        Common::log("Subscriber Export","Subscribers exported as excel file",new NewsletterSubscriber());
        return $this->excel->download(new NewsletterExport, 'subscribers-'.date('y-m-d').'.xlsx');
    }

}
