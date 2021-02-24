<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\{
    Http\Resources\Json\JsonResource,
    Http\Request,
    Http\Response
};
use App\Api\{
    User,
    LoyaltyLevel,
    LoyaltyLevelLang
};
use FileHelper;
use Common;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        //return parent::toArray($request);

        $loyalty_levels = LoyaltyLevel::where('status','=',ITEM_ACTIVE)->get();

        $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
        $loyalty_level = $loyalty_level->first();
        $loyalty_level_id_user = ($loyalty_level === null) ? '' : $loyalty_level->loyalty_level_id;

        $points_required = array();
        foreach( $loyalty_levels as $loyalty_level ) {
            $loyalty_level_id = ( $loyalty_level->loyalty_level_id ) ? $loyalty_level->loyalty_level_id : '';
            $loyalty_level_name = ( $loyalty_level->loyalty_level_id > 0 ) ? LoyaltyLevelLang::where('loyalty_level_id',$loyalty_level_id)->where('language_code','en')->value('loyalty_level_name') : '';
            if( $loyalty_level_id > $loyalty_level_id_user ) {
                $points_required[$loyalty_level_name] = $loyalty_level->from_point - (int)$this->total_loyalty_points;
            }
        }

        return [
            //'branch_id' => $this->branch_id,
            'user_id' => $this->user_id,
            'user_key' => $this->user_key,            
            'first_name' => ($this->first_name === null) ? '' : $this->first_name,
            'last_name' => ($this->last_name === null) ? '' : $this->last_name,
            'username' => ($this->username === null) ? '' : $this->username,
            'email' => ($this->email === null) ? '' : $this->email,
            'profile_image' => FileHelper::loadImageProfile($this->profile_image),
            'phone_number' => ($this->phone_number === null) ? '' : $this->phone_number,
            'dob' => ($this->dob === null) ? '' : $this->dob,
            'gender' => ($this->gender === null) ? '' : Common::gender($this->gender),
            'access_token' => ($this->access_token === null) ? $request->bearerToken() : $this->access_token,
            'wallet_amount' => Common::currency($this->wallet_amount),
            'loyalty_points' => ($this->loyalty_points !== null) ? (int)$this->loyalty_points : 0,
            'loyalty_level_name' => $this->when(true,function() {
                $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
                LoyaltyLevelLang::selectTranslation($loyalty_level);
                $loyalty_level = $loyalty_level->first();
                return ($loyalty_level === null) ? '' : $loyalty_level->loyalty_level_name;
            }),  
            'user_since' => ($this->created_at === null) ? '' : date( 'd-m-Y H:i:s', strtotime( $this->created_at ) ), 
            'points_required_to _reach_other_levels' => $points_required,
            'card_number' => ($this->card_number === null) ? '' : $this->card_number         
        ];
    }
    
    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function  with($request)
    {
        return [
            'status' => Response::HTTP_OK,
            'time' => strtotime(date('Y-m-d H:i:s')),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'kjh');
    }
    
}
