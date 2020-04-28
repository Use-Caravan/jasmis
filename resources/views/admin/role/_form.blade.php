{{ Form::open(['url' => $url, 'id' => 'role-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">        
       
        <div class="form-group {{ ($errors->has("role_name")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("role_name", __('admincrud.Role Name'), ['class' => 'required']) }}
                {{ Form::text("role_name", $model->role_name, ['class' => 'form-control']) }} 
                @if($errors->has("role_name"))
                    <span class="help-block error-help-block">{{ $errors->first("role_name") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                @php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE @endphp
                {{ Form::label("status", __('admincommon.Status'), ['class' => 'required']) }}
                {{ Form::radio('status', ITEM_ACTIVE, ($model->status == ITEM_ACTIVE), ['class' => 'hide','id'=> 'statuson' ]) }}
                {{ Form::label("statuson", __('admincommon.Active'), ['class' => ' radio']) }}

                {{ Form::radio('status', ITEM_INACTIVE, ($model->status == ITEM_INACTIVE), ['class' => 'hide','id'=>'statusoff']) }}
                {{ Form::label("statusoff", __('admincommon.Inactive'), ['class' => 'radio']) }}
                @if($errors->has("status"))
                    <span class="help-block error-help-block">{{ $errors->first("status") }}</span>
                @endif                    
            </div>
        </div>   
        {{ Form::hidden('permission',$model->permission,["id" => "role-backend_role_json"]) }}
        <div class="form-group">
            <div id="role-list"></div>        
        </div>
    </div>    
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('role.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}
{!! JsValidator::formRequest('App\Http\Requests\Admin\RoleRequest', '#role-form')  !!}

<script type="text/javascript">
$(document).ready(function()
{
    window.selected_data = '';

    var data = @json($ruleList),
        treeConfig = [{
            treeId: 'role-list',
            input: $('#role-backend_role_json'),
            data: data,
            selected: []
        }],
        selected,
        treeObj;

    /**
        * @link: https://stackoverflow.com/a/12825917/5798881
        */    
    treeConfig.forEach(function (config) {
        selected = config.input.val();
        if (selected === '') {
            selected = '[]';
        }           

        config.selected = JSON.parse(selected);

        treeObj = $('#' + config.treeId);

        treeObj.jstree({
            "plugins": ["checkbox"],
            'core': {
                'data': config.data
            }
        }).bind("loaded.jstree", function (e, data) {
            selected = [];
            config.selected.forEach(function (value) {

                if (parseInt(value.state) === 1) {
                    selected.push(value.id);
                }
            });
            $(this).jstree("select_node", selected);
        });
    });
    
    $('.listTree ul li ul li span input').click(function () {
        $(this).addClass("clickedOnChild");
    });
    $('.listTree ul li ul li span').click(function () {
        var checkBoxes = $(this).find("input");
        // Avoid if  user directly clicked on the checkbox
        if (checkBoxes.hasClass("clickedOnChild")) {
            checkBoxes.removeClass("clickedOnChild");
            return;
        }
        checkBoxes.click();
    });


    $(".jstree").on("changed.jstree", function (e, data) {
        selected_data = data.selected;
    });
    
    var treeJson, treeJsonObj;
    $('form#role-form').on('submit', function (e) {
        //e.preventDefault();
        if (selected_data.length == 0) {
            Core.error('Role cannot be created or updated with empty data');
            return false;
        }
        treeConfig.forEach(function (config) {
            treeJson = [];
            treeJsonObj = $('#' + config.treeId).jstree(true).get_json('#');
            
            treeJsonObj.forEach(function (value) {
                value.children.forEach(function (childValue) {
                    treeJson.push({
                        id: childValue.id,
                        state: (childValue.state.selected == true) ? 1 : 0
                    })
                });
            });
            console.log(treeJson);
            config.input.val(JSON.stringify(treeJson));
        });    
        
    });
});
</script>
    