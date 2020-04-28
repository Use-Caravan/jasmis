{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resources/assets/export-media-assets/print.css')}} ">
</head>
<body> --}}  




    @php  $count = 1; @endphp
    @foreach($vouchersList['vouchers'] as $key => $voucher)
    @php  $count++; @endphp

<table cellspacing="0" cellpadding="0" class="table" style="background: {{ ($count === 1 ? '#6A4BC2' : ($count === 2 ? '#D13286' : '#12BF87') ) }} ">
  <tbody>
    <tr>
      <td>
        <table cellspacing="0" cellpadding="0" class="table-inside">
          <tbody>
            <tr>
              <td><img src="{{ asset($vouchersList['vendor_logo']) }}" class="main_logo" width="90" style="height: auto;"></td>
            </tr>
            <tr>
              <td style="display: inline-block; width: 100%; font-size: 25px; font-weight: 600; color: #fff">
                {{$voucher['item_name']}}
              </td>
            </tr>
            <tr>
              <td>
                <span style="background: #fff; font-weight: 600; font-size: 35px; color: #000; text-align: left; width: 200px; "> {{$voucher['voucher_code']}} </span>
              </td>
            </tr>
            <tr>
              <td>
                <p class="validity">Validity Upto: {{$voucher['valid_upto']}}</p>
              </td>
            </tr>
            <tr>
              <td>
                <p class="terms">Terms and conditions apply, no refund on this voucher</p>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
      <td style="background-image:url({{ asset($voucher['item_image']) }}); background-repeat: no-repeat; background-size: cover; background-position: center right; width: 200px; vertical-align: top; text-align: right; background-image-resize: 5;">
        <img src="{{ asset($vouchersList['corporate_logo']) }}" class="vendor_logo" style="height: auto; width: auto; max-width: 80px; max-height: 80px">
      </td>
    </tr>
  </tbody>
</table>

    @php  if($count === 3) { $count = 0; }  @endphp
    @endforeach

{{-- </body>
</html> --}}