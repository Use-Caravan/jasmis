<div class="box">
    <h4 class="mb-0">{{__('Ratings')}}</h4>
    <a href="javascript:" data-toggle="modal" data-target="{{ (Auth::guard(GUARD_USER)->check()) ? "#modal-rating" : "#login_modal" }}" ><i class="material-icons">add_circle_outline</i></a>
</div>
<ul class="reset">
    @foreach($branchDetails->rating as $key => $value)
    <li>
        <div class="border-box wow fadeInUp">
            <div class="icon"><i class="material-icons fly_icon">person_outline</i></div>
            <h5>{{$value->name}}</h5>
            <div class="star-row">
                <form>
                    <span class="star-rating view_only">
                            @for($i = 1; $i <= 5; $i++ )
                            <input id="star-{{$branchDetails->branch_key}}" type="checkbox" {{ ( (int)$value->rating == $i) ? 'checked="true"' : '' }} name="star">
                            <label class="star" for="star-{{$branchDetails->branch_key}}" ></label>
                        @endfor
                    </span>
                </form>
            </div>
            <p>{{ $value->review }}</p>
            <span class="date"> {{ $value->created_date }} </span>
        </div>
    </li>
    @endforeach
    {{--
    <li>
        <div class="border-box wow fadeInUp">
            <div class="icon"><i class="material-icons fly_icon">person_outline</i></div>
            <h5>Pieterson</h5>
            <div class="star-row">
                <form>
                    <span class="star-rating view_only">
                        <input id="star-5" type="radio" name="star">
                        <label class="star" for="star-5"></label>
                        <input id="star-4" type="radio" checked="" name="star">
                        <label class="star" for="star-4"></label>
                        <input id="star-3" type="radio" name="star">
                        <label class="star" for="star-3"></label>
                        <input id="star-2" type="radio" name="star">
                        <label class="star" for="star-2"></label>
                        <input id="star-1" type="radio" name="star">
                        <label class="star" for="star-1"></label>
                    </span>
                </form>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetur elit. Mauris commodo magna at feils
            </p>
            <span class="date">01/02/2019</span>
        </div>
    </li>


    <li>
        <div class="border-box wow fadeInUp">
            <div class="icon"><i class="material-icons fly_icon">person_outline</i></div>
            <h5>Pieterson</h5>
            <div class="star-row">
                <form>
                    <span class="star-rating view_only">
                        <input id="star-5" type="radio" name="star">
                        <label class="star" for="star-5"></label>
                        <input id="star-4" type="radio" checked="" name="star">
                        <label class="star" for="star-4"></label>
                        <input id="star-3" type="radio" name="star">
                        <label class="star" for="star-3"></label>
                        <input id="star-2" type="radio" name="star">
                        <label class="star" for="star-2"></label>
                        <input id="star-1" type="radio" name="star">
                        <label class="star" for="star-1"></label>
                    </span>
                </form>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetur elit. Mauris commodo magna at feils
            </p>
            <span class="date">01/02/2019</span>
        </div>
    </li>


    <li>
        <div class="border-box wow fadeInUp">
            <div class="icon"><i class="material-icons fly_icon">person_outline</i></div>
            <h5>Pieterson</h5>
            <div class="star-row">
                <form>
                    <span class="star-rating view_only">
                        <input id="star-5" type="radio" name="star">
                        <label class="star" for="star-5"></label>
                        <input id="star-4" type="radio" checked="" name="star">
                        <label class="star" for="star-4"></label>
                        <input id="star-3" type="radio" name="star">
                        <label class="star" for="star-3"></label>
                        <input id="star-2" type="radio" name="star">
                        <label class="star" for="star-2"></label>
                        <input id="star-1" type="radio" name="star">
                        <label class="star" for="star-1"></label>
                    </span>
                </form>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetur elit. Mauris commodo magna at feils
            </p>
            <span class="date">01/02/2019</span>
        </div>
    </li>
    --}}
</ul>
@if($itemDetails != null)
<!-- Rating modal -->
<div class="modal modal_ratings fade" id="modal-rating">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                <h5 class="modal-title">{{__('Add your rating')}}</h5>
            </div>
            <div class="modal-body">
                <div class="full_row">
                    <form action="{{ route('frontend.post-rating') }}" id="post-rating" method="POST">
                        <input type="hidden" name="branch_key" value="{{ $branchDetails->branch_key }}">
                        <div class="form-group text-center  star-row">                            
                            <span class="star-rating large">
                                <input id="rating-5" type="radio" value="5" name="rating">
                                <label class="star" for="rating-5"></label>
                                <input id="rating-4" type="radio" value="4" name="rating">
                                <label class="star" for="rating-4"></label>
                                <input id="rating-3" type="radio" value="3"  name="rating">
                                <label class="star" for="rating-3"></label>
                                <input id="rating-2" type="radio" value="2" name="rating">
                                <label class="star" for="rating-2"></label>
                                <input id="rating-1" type="radio" value="1" name="rating">
                                <label class="star" for="rating-1"></label>
                            </span>                        
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="review" placeholder="{{__('Enter your comments')}}"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button class="shape-btn shape1 shape-dark" data-dismiss="modal"><span class="shape">{{__('Cancel')}}</span></button>
                            <button type="submit" class="shape-btn loader shape1"><span class="shape">{{__('Submit')}}</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<span> <h2 class="heading-1">{{__('No items found you can not give rating.')}}<h2></span>
@endif
<!-- Rating modal -->