<?php

namespace Modules\Orders\Http\Rules;

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Constants\Status;
use Illuminate\Contracts\Validation\Rule;

class ValidCoupon implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $coupon = Coupon::where('code',$value)->first();

        if(empty($coupon)){
          return false;
        }

        if($coupon->status != Status::ACTIVE){
          return false;
        }

        if($coupon->start_date){
          if(now() < $coupon->start_date){
            return false;
          }
        }

        if($coupon->end_date){
          if(now() > $coupon->end_date){
            return false;
          }
        }

        if($coupon->max_uses){
          if($coupon->usage_count >= $coupon->max_uses){
            return false;
          }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Coupon not valid';
    }
}
