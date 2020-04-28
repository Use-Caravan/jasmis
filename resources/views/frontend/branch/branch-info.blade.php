<div class="detail-info">
    <div class="box-border wow fadeInUp">
        <h4 class="mb-0">{{__('Info')}}</h4>
    </div>

    <div class="bordered wow fadeInUp">
        <h5>{{$branchDetails->branch_name}}</h5>

        <p class="f18">{{ $branchDetails->branch_cuisine }}</p>

        <p>{{ $branchDetails->branch_description }}</p>
    </div>

    <div class="row wow fadeInUp">
        <div class="col-md-6">
            <div class="bordered shadow-sm">
                <h4>{{__('Delivery Hours')}}</h4>
                <table>
                    <tbody>
                        @foreach($branchDetails->time_info->delivery as $key => $value)
                        <tr class="{{ date("N", strtotime(date('Y-m-d H:i:s'))) == $value->day_no ? 'active' : '' }}">
                            <td>{{ $value->day_name }}</td>
                            <td>{{ $value->time_slot }}</td>
                        </tr>
                        @endforeach

                        {{-- <tr>
                            <td>Tuesday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr class="active">
                            <td>Wednesday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Thursday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Friday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td>00.00-23.00</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bordered shadow-sm">
                <h4>{{__('Pickup Hours')}}</h4>
                <table>
                    <tbody>
                        @foreach($branchDetails->time_info->pickup as $key => $value)
                        <tr class="{{ date("N", strtotime(date('Y-m-d H:i:s'))) == $value->day_no ? 'active' : '' }}">
                            <td>{{ $value->day_name }}</td>
                            <td>{{ $value->time_slot }}</td>
                        </tr>
                        @endforeach

                        {{-- <tr>
                            <td>Tuesday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr class="active">
                            <td>Wednesday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Thursday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Friday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td>00.00-23.00</td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td>00.00-23.00</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="full_row wow fadeInUp padding-15  shadow-sm mt-15">
        <ul class="detail-price reset">
            <li>
                <div class="icon"><i class="fee"></i></div>
                <div class="name">{{__('Delivery fee')}}</div>
                <div class="price">{{ $branchDetails->delivery_cost }}</div>
            </li>
            <li>
                <div class="icon"><i class="material-icons">access_time</i></div>
                <div class="name">{{__('Pickup Time')}}</div>
                <div class="price">{{ $branchDetails->pickup_time." Mins" }}</div>
            </li>
            <li>
                <div class="icon"><i class="material-icons">access_time</i></div>
                <div class="name">{{__('Delivery Time')}}</div>
                <div class="price">{{ $branchDetails->delivery_time." Mins" }}</div>
            </li>
            <li>
                <div class="icon"><i class="min_ord"></i></div>
                <div class="name">{{__('Min Order')}}</div>
                <div class="price">{{ $branchDetails->min_order_value }}</div>
            </li>
            <li>
                <div class="icon"><i class="fee"></i></div>
                <div class="name">{{__('Payment')}}</div>
                <div class="price">{{ $branchDetails->payment_option }}</div>
            </li>
        </ul>
    </div>

    <div class="full_row wow fadeInUp address mt-15">
        <h4><i class="icon-location-pin" aria-hidden="true"></i> {{__('Address')}}</h4>
        <address>
            {{ $branchDetails->branch_address }}
            {{-- Bldg 1023 seef district, <br>Manama --}}
        </address>
    </div>

</div>