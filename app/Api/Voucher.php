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
        $query = Voucher::select([
                Voucher::tableName().".voucher_key",
                Voucher::tableName().".promo_code",
                Voucher::tableName().".discount_type",
                Voucher::tableName().".limit_of_use",
                Voucher::tableName().".max_redeem_amount",
                Voucher::tableName().".value",
                Voucher::tableName().".min_order_value",
                Voucher::tableName().".expiry_date",
                VoucherBeneficiary::tableName().".beneficiary_id",
                Branch::tableName().".branch_key",
                Branch::tableName().".branch_slug",
            ])
        ->leftJoin(VoucherBeneficiary::tableName(),Voucher::tableName().".voucher_id",VoucherBeneficiary::tableName().".voucher_id")
        ->leftJoin(Branch::tableName(),VoucherBeneficiary::tableName().".beneficiary_id",Branch::tableName().".branch_id")
        ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",Vendor::tableName().".vendor_id")
        ->where([
            Voucher::tableName().".status" => ITEM_ACTIVE,            
        ])
        ->where('expiry_date', '>=', date('Y-m-d H:i:s'))
        ->Where(function($query) {
           $query->where(Voucher::tableName().".promo_for_shops",PROMO_FOR_ALL_SHOPS)           
           ->orWhere(Branch::tableName().".branch_key" , request()->branch_key);
        });
        VendorLang::selectTranslation($query);
        return $query;
    }
}
