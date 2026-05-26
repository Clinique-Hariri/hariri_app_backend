<?php

namespace Modules\Orders\Services;
use App\Models\Setting;
use Modules\Coupons\Models\Coupon;
use Modules\Orders\Http\Rules\ValidCoupon;

class AmountCalculatorService
{

  private $purchase_amount;
  private $delivery_amount = 0;
  private $discount_amount = 0;
  private $distance_amount = 0;
  private $total_amount = 0;

  public function __construct($items)
  {
    $this->purchase_amount = $items->sum(function ($item) {
      return $item->total_price;
    });
  }

  public function calculateDeliveryAmount($end_point)
  {
    $start_point = [
      'longitude' => '6',
      'latitude' => '33'
    ];
    $this->distance_amount = $this->calc_distance($start_point, $end_point);
    $this->delivery_amount = $this->calc_price($this->distance_amount);
    return $this;
  }

  public function applyCoupon($code)
  {

    $coupon = Coupon::where('code', $code)->first();
    $this->discount_amount = $this->purchase_amount * ($coupon->discount / 100);

    return $this;
  }

  public function getPurchaseAmount(){
    return $this->purchase_amount;
  }

  public function setPurchaseAmount($amount){
    $this->purchase_amount = $amount;
    return $this;
  }

  public function getDistanceAmount(){
    return $this->distance_amount;
  }

  public function setDistanceAmount($amount){
    $this->distance_amount = $amount;
    return $this;
  }

  public function getDeliveryAmount(){
    return $this->delivery_amount;
  }

  public function setDeliveryAmount($amount){
    $this->delivery_amount = $amount;
    return $this;
  }

  public function getDiscountAmount(){
    return $this->discount_amount;
  }

  public function setDiscountAmount($amount){
    $this->discount_amount = $amount;
    return $this;
  }

  public function getTotalAmount(){
    $this->total_amount = $this->purchase_amount - $this->discount_amount + $this->delivery_amount;
    return $this->total_amount;
  }

  public static function calc_distance($p1, $p2)
  {
    $r = 6378137;
    $dLat = deg2rad($p2['latitude'] - $p1['latitude']);
    $dLong = deg2rad($p2['longitude'] - $p1['longitude']);

    $a = sin($dLat / 2) * sin($dLat / 2) +
      cos(deg2rad($p1['latitude'])) * cos(deg2rad($p2['latitude'])) *
      sin($dLong / 2) * sin($dLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $r * $c;
    return $d;
  }

  public static function calc_price($distance)
  {

    $price_per_km = Setting::getValue('price_per_km') ?? 0;
    $min_price = Setting::getValue('min_price') ?? 0;
    $max_price = Setting::getValue('max_price') ?? 0;


    $true_price = ($distance / 1000) * $price_per_km;
    $price = min(max($true_price, $min_price), $max_price);
    return $price;
  }
}
