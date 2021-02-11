<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;
use App\Voucher as CommonVoucher;
use App\{
    VoucherBeneficiary,
    Vendor,
    BranchLang,
    VendorLang
};

class Voucher extends CommonVoucher
{
    public static function getBranchVouchers()
    {   
        $user_id = request()->user()->user_id;

        $query = Voucher::select([
                Voucher::tableName().".voucher_key",
                Voucher::tableName().".promo_code",
                Voucher::tableName().".discount_type",
                Voucher::tableName().".limit_of_use",
                Voucher::tableName().".max_redeem_amount",
                Voucher::tableName().".value",
                Voucher::tableName().".min_order_value",
                Voucher::tableName().".expiry_date",
                Voucher::tableName().".app_type",
                VoucherBeneficiary::tableName().".beneficiary_id",
                Branch::tableName().".branch_key",
                Branch::tableName().".branch_slug",
            ])
        ->leftJoin(VoucherBeneficiary::tableName(),Voucher::tableName().".voucher_id",VoucherBeneficiary::tableName().".voucher_id")
        ->leftJoin(VoucherUsage::tableName(),Voucher::tableName().".voucher_id",VoucherUsage::tableName().".voucher_id")
        ->leftJoin(Branch::tableName(),VoucherBeneficiary::tableName().".beneficiary_id",Branch::tableName().".branch_id")
        ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",Vendor::tableName().".vendor_id")
        ->where([
            Voucher::tableName().".status" => ITEM_ACTIVE,            
        ])
        ->where('expiry_date', '>=', date('Y-m-d H:i:s'))
        ->where('app_type', 'LIKE', "%".request()->user()->device_type."%")
        ->where(Voucher::tableName().".apply_promo_for",VOUCHER_APPLY_PROMO_USERS)
        ->where(Voucher::tableName().".promo_for_user",PROMO_USER_ALL)
        //->Where(VoucherBeneficiary::tableName().".beneficiary_id",$user_id)
        //->Where(function($query) {
           /*$query->where(Voucher::tableName().".promo_for_shops",PROMO_FOR_ALL_SHOPS)           
           ->orWhere(Branch::tableName().".branch_key" , request()->branch_key);*/
           //$query->where(Voucher::tableName().".promo_for_user",PROMO_USER_ALL)           
           //->orWhere(VoucherBeneficiary::tableName().".beneficiary_id" , request()->user()->user_id);
           ->orWhere(function($query) use ($user_id)
              {
                  $query->Where(Voucher::tableName().".promo_for_user",PROMO_USER_PARTICULAR)
                        ->Where(VoucherBeneficiary::tableName().".beneficiary_id",$user_id);
              })
           
        //})
        ->groupBy(Voucher::tableName().".voucher_id");

        VendorLang::selectTranslation($query);
        //echo $query->toSql();exit;
        return $query;
    }
}
