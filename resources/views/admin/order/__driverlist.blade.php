<div class="row"> 
    @foreach($driverslist as $key => $value)
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th><p>Name:</p></th>
                    <th><p>Mobile:</p></th>
                    <th><p>Status:</p></th>
                    <th><p>Action:</p></th>
                </tr>
                <tr>                    
                    <td>
                        <b><p>{{$value['name']}}</p></b>
                    </td>                 
                    <td>
                        <b><p>{{$value['phone_number'] }}</p></b>
                    </td>                                     
                    <td>
                        <b><p>{{$deliveryboy->onlineStatus($value['status'])}}</p></b>
                    </td>
                    <td>
                        <span data-order_key="{{$order_key}}" data-deliveryboy_key="{{$value['_id']}}" class="pull-right delivery-boy-assign btn btn-success ">Assign</span>
                    </td>
                </tr>                                    
            </tbody>
        </table>
    </div>
    @endforeach    
</div>