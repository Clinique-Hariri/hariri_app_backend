<?php

namespace Modules\Coupons\Http\Controllers;

use App\Traits\RandomTrait;
use Illuminate\Http\Request;
use Modules\Coupons\Models\Coupon;
use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Modules\Coupons\Datatables\CouponDatatable;
use Modules\Coupons\Http\Requests\EditCouponRequest;
use Modules\Coupons\Http\Requests\CreateCouponRequest;

class CouponController extends Controller
{

  use RandomTrait;
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new CouponDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("coupons::index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }

    do {
      $code = $this->random(6, 'uppercase_alphanumeric');
    } while (Coupon::where('code', $code)->exists());
    return view("coupons::create-edit")->with(["edit" => false, 'code' => $code]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }
    return view("coupons::create-edit")->with(["edit" => true, "model" => Coupon::findOrFail($id)]);
  }

  public function store(CreateCouponRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $coupon = Coupon::create($request->validated());
      return redirect()->route('coupons.index')->with('success', __('app.created_successfully', ['name' => __('coupons::app.coupon')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditCouponRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $coupon = Coupon::findOrFail($id);
      $coupon->update($request->validated());
      return redirect()->route('coupons.index')->with('success', __('app.updated_successfully', ['name' => __('coupons::app.coupon')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $coupon = Coupon::findOrFail($id);
      $coupon->delete();
      return redirect()->route('coupons.index')->with('success', __('app.deleted_successfully', ['name' => __('coupons::app.coupon')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
