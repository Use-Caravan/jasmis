{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resources/assets/export-media-assets/print.css')}} ">
</head> --}}
<body>  
    @php  $count = 1; @endphp
    @foreach($vouchersList['vouchers'] as $key => $voucher)
    @php  $count++; @endphp
    <div class="new_box {{ ($count === 1 ? 'blue' : ($count === 2 ? 'pink' : 'green') ) }}">
        <div class="left-side">
            <img src="{{ asset($vouchersList['vendor_logo']) }}" class="logo_2">
            <h2 class="heading">{{$voucher['item_name']}}</h2>
            <p class="nums">{{$voucher['voucher_code']}}</p>
            <p class="small-coupon"> Validity Upto: {{$voucher['valid_upto']}}</p>
            <p class="terms"> terms and conditions apply, no refund on this voucher</p>
        </div>
        <div class="right-side" style="background-image: url({{ asset($vouchersList['corporate_logo']) }});">
            <div class="image-banners">
                <img src="{{ asset($voucher['item_image']) }}">
            </div>             
        </div>
    </div>
    @php  if($count === 3) { $count = 0; }  @endphp
    @endforeach
</body>
{{-- </html> --}}