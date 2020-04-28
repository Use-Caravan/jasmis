<div id="delete_confirm" class="hide">
    <button class="btn grey">@lang('admincommon.No')</button>
    <button class="btn green" id="deleteConfirm">@lang('admincommon.Yes')</button>
    
</div> <!--delete_confirm-->
</div>
{!! AssetHelper::loadAdminAsset() !!}
@php 
$user_id = Auth::guard(APP_GUARD)->user()->vendor_id;
$app_type = APP_TYPE_WEB;
@endphp
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('webconfig.map_key') }}&callback=initMap&libraries=drawing,places,geometry&sensor=false"></script>
<link rel="manifest" href="/manifest.json" />
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
var OneSignal = window.OneSignal || [];
OneSignal.push(function() {
    OneSignal.init({
        appId: "bb42b8c9-7dd8-45e4-9302-de954ddc1c5a",
    });
    OneSignal.on('subscriptionChange', function (isSubscribed) {
        OneSignal.push(function() {
            OneSignal.getUserId(function(userId) {
            var appId = userId;
            var id = "{{ $user_id }}";
            var app_type = "{{$app_type}}";
                $.ajax({
                    url: " {{ url('admin/webpush-notification/register') }}",
                    type: 'post',
                    data: { "appId": appId, user_id: id, status : isSubscribed },
                    success: function (result) {
                        console.log(result);
                    }
                });
            });
        });
    })
});
</script>
</body>
</html>
