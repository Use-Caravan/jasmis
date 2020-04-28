<?php

namespace App\Api;

use App\{
    BranchReview as CommonBranchReview,
    Api\User,
    Http\Resources\Api\V1\BranchRatingResource
};

class BranchReview extends CommonBranchReview
{    
    public static function getBranchReviews( $branchId = null )
    {  
        $user = new User();
        $userTable = $user->getTable();
        $branchReview = new self();
        $branchReviewTable = $branchReview->getTable(); 
        $query = self::select([
            "$userTable.first_name",
            "$userTable.last_name",
            "$branchReviewTable.rating",
            "$branchReviewTable.review",
            "$branchReviewTable.created_at"
        ])
        ->leftjoin($userTable,"$branchReviewTable.user_id","$userTable.user_id")
        ->where([
            "$branchReviewTable.approved_status" => ITEM_APPROVED,
            "$branchReviewTable.status" => ITEM_ACTIVE            
            ]);
        if($branchId !== null) {
            
            $query = $query->where([$branchReview->getTable().".branch_id" => $branchId]);
        }
        return BranchRatingResource::collection($query->get());
    }                    
                
}
    