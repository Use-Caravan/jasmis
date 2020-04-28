<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\LoyaltyPointResource
};
use App\Api\{
    LoyaltyLevel
    
};
use Auth;
use App\User as AppUser;

class LoyaltyPointController extends Controller
{
    public function loyaltyPointDetails()
    {   
        $user = request()->user();  
        $this->setData(new LoyaltyPointResource($user));
        return $this->asJson();
    }
}
