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

class LoyaltyPointResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
        $loyalty_level = $loyalty_level->first();
        $loyalty_point_per_bd = ($loyalty_level === null) ? '' : $loyalty_level->loyalty_point_per_bd;

        $redeem_amount_per_point = ($loyalty_level === null) ? '' : $loyalty_level->redeem_amount_per_point;
        $loyalty_point_per_bd = ($loyalty_level === null) ? '' : $loyalty_level->loyalty_point_per_bd;
        $minimum_amount_to_redeem = ($loyalty_level === null) ? '' : $loyalty_level->minimum_amount_to_redeem;
        $points_to_redeem_1BD = '';
        if( $redeem_amount_per_point > 0 )
        {
           $points_to_redeem_1BD = ( $redeem_amount_per_point < 1000 ) ? ( 1000 / $redeem_amount_per_point ) : '';
        }

        return [
            'user_key' => $this->user_key, 
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,           
            'created_year' => date("Y", strtotime($this->created_at)),           
            'card_number' => ($this->card_number !== null) ? $this->card_number : 0,
            'loyalty_points' => ($this->loyalty_points !== null) ? (int)$this->loyalty_points : 0,
            'total_loyalty_points' => ($this->total_loyalty_points !== null) ? (int)$this->total_loyalty_points : 0,
            'converted_loyalty_points' => $this->total_loyalty_points - $this->loyalty_points,
            'loyalty_level_name' => $this->when(true,function() {
                $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
                LoyaltyLevelLang::selectTranslation($loyalty_level);
                $loyalty_level = $loyalty_level->first();
                // if($loyalty_level === null) {
                //     $loyalty_level = LoyaltyLevel::select('loyalty_level_name');
                //     LoyaltyLevelLang::selectTranslation($loyalty_level);
                //     $loyalty_level = $loyalty_level->orderBy(LoyaltyLevel::tableName().'.loyalty_level_id','asc')->first();
                // }
                return ($loyalty_level === null) ? '' : $loyalty_level->loyalty_level_name;
            }),  
            'next_level_points' => $this->when(true,function() {
                $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
                $loyalty_level = $loyalty_level->first(); 

                if($loyalty_level != null){
                     $next_loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$loyalty_level->to_point+1)->where('to_point','>=',(int)$loyalty_level->to_point+1)->first();
                
                    if($next_loyalty_level === null){
                        return '';
                    }
                
                    return ($loyalty_level->to_point+1) - $this->total_loyalty_points;
                }

            }),
            'next_level_name' => $this->when(true,function() {
                
                $loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points);
                $loyalty_level = $loyalty_level->first(); 

                if($loyalty_level != null){
                    $next_level = ($loyalty_level->to_point+1);

                    $next_loyalty_level = LoyaltyLevel::where('from_point','<=',(int)$next_level)->where('to_point','>=',(int)$next_level);
                    LoyaltyLevelLang::selectTranslation($next_loyalty_level);
                    $next_loyalty_level = $next_loyalty_level->first();
                    
                    return ($next_loyalty_level === null) ? '' : $next_loyalty_level->loyalty_level_name;
                }
            }),
             'card_image' => $this->when(true,function() {
                $result = LoyaltyLevel::select('card_image')->where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points)->first();                
                if($result === null) {
                    $result = LoyaltyLevel::select('card_image')->orderBy('loyalty_level_id','asc')->first();                  
                }                
                return  FileHelper::loadImage( ($result === null) ? '' :  $result->card_image );
            }), 
            'popup_image' => $this->when(true,function() {
                $result = LoyaltyLevel::select('popup_image')->where('from_point','<=',(int)$this->total_loyalty_points)->where('to_point','>=',(int)$this->total_loyalty_points)->first();                
                if($result === null) {
                    $result = LoyaltyLevel::select('popup_image')->orderBy('loyalty_level_id','asc')->first();                  
                }                
                return  FileHelper::loadImage( ($result === null) ? '' :  $result->popup_image );
            }), 
            'redeem_amount_per_point' => $redeem_amount_per_point.' Fils',
            'loyalty_point_per_bd' => $loyalty_point_per_bd,
            'minimum_amount_to_redeem' => $minimum_amount_to_redeem.' BD',
            'points_for_every_1BD' => $loyalty_point_per_bd,
            'points_to_redeem_1BD' => $points_to_redeem_1BD
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
