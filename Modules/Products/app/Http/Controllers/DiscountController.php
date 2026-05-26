<?php

namespace Modules\Products\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Products\Http\Requests\CreateDiscountRequest;
use Modules\Products\Http\Requests\EditDiscountRequest;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use Modules\Products\Models\Discount;
use Modules\Products\Datatables\DiscountDatatable;

class DiscountController extends Controller
{
  public function index(Request $request, $product_id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $product = Product::findOrFail($product_id);

      $datatable = new DiscountDatatable();
      if ($request->wantsJson()) {
        return $datatable->datatables($request);
      }

      return view("products::discount.index")->with([
        "columns" => $datatable::columns(),
        "product" => $product,
      ]);

    } catch (Exception $e) {
      return redirect()->route('pages-misc-error');
    }
  }

  public function create(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    try {

      $product = Product::findOrFail($request->product_id);

      return view('products::discount.create-edit')->with([
        "edit" => false,
        'product' => $product
      ]);

    } catch (Exception $e) {
      return redirect()->route('pages-misc-error');
    }
  }

  public function store(CreateDiscountRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      Discount::create($request->validated());

      return redirect()->route('discounts.index', $request->product_id)
        ->with('success', __('app.created_successfully', ['name' => __('products::app.discount')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $discount = Discount::findOrFail($id);
      $product = $discount->product;

      return view('products::discount.create-edit')->with([
        'edit' => true,
        'model' => $discount,
        'product' => $product
      ]);
    } catch (Exception $e) {
      return redirect()->route('pages-misc-error');
    }
  }

  public function update(EditDiscountRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      $model = Discount::findOrFail($id);

      $model->update($request->validated());

      return redirect()->route('discounts.index', $model->product_id)
        ->with('success', __('app.updated_successfully', ['name' => __('products::app.discount')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Discount::findOrFail($id);
      $product_id = $model->product_id;
      $model->delete();

      return redirect()->route('discounts.index', $product_id)
        ->with('success', __('app.deleted_successfully', ['name' => __('products::app.discount')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
