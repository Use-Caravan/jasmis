<div class="account-links">
    <ul class="reset wow fadeInUp">
        <li> <a class="{{ (Route::currentRouteName() == "frontend.myorder") ? "active" : '' }}" href="{{route('frontend.myorder')}}"><i class="icon-notebook"></i> {{__('My Orders')}}  </a></li>
        <li> <a class="{{ (Route::currentRouteName() == "address.index") ? "active" : '' }}" href="{{route('address.index')}}"><i class="icon-location-pin"></i> {{__('Address Book')}}  </a></li>
        <li> <a class="{{ (Route::currentRouteName() == "frontend.wishlist") ? "active" : '' }}" href="{{route('frontend.wishlist')}}"><i class="icon-heart"></i> {{__('Favourite Restaurants')}} </a></li>
        <li> <a class="{{ (Route::currentRouteName() == "frontend.wallet") ? "active" : '' }}" href="{{route('frontend.wallet')}}"><i class="icon-wallet"></i> {{__('C wallet')}}  </a></li>
        <li> <a class="{{ (Route::currentRouteName() == "frontend.loyalty-points") ? "active" : '' }}" href="{{route('frontend.loyalty-points')}}"><i class="icon-star"></i> {{__('Loyalty Points')}}  </a></li>
        <li> <a class="{{ (Route::currentRouteName() == "frontend.signout") ? "active" : '' }}" href="{{route('frontend.signout')}}"><i class="icon-logout"></i> {{__('Logout')}}  </a></li>
    </ul>
</div>                