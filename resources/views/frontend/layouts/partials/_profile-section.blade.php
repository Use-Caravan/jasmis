<div class="profile-user">
    <h4 class="wow fadeInUp">{{Auth::guard(GUARD_USER)->user()->first_name.Auth::guard(GUARD_USER)->user()->last_name}}</h4>
    <p class="wow fadeInUp">{{Auth::guard(GUARD_USER)->user()->email}}</p>
    <p class="wow fadeInUp">{{Auth::guard(GUARD_USER)->user()->phone_number}}</p>
    <button class="shape-btn shape1 wow fadeInUp shape-dark" data-toggle="modal" data-target="#edit-profile"><span class="shape">{{__('Edit Profile')}}</span></button>
</div>