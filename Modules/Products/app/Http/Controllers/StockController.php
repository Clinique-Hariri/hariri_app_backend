<?php

namespace Modules\Products\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Products\Models\Stock;
use App\Http\Controllers\Controller;
use Modules\Products\Constants\Type;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use Modules\Products\Datatables\StockDatatable;
use Modules\Products\Http\Requests\EditStockRequest;
use Modules\Products\Http\Requests\CreateStockRequest;
use Modules\Purchases\Traits\BatchNumberGeneratorTrait;

class StockController extends Controller
{
  use BatchNumberGeneratorTrait;
  public function index(Request $request, $product_id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      $product = Product::findOrFail($product_id);

      $datatable = new StockDatatable();
      if ($request->wantsJson()) {
        return $datatable->datatables($request);
      }

      return view("products::stock.index")->with([
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

      return view('products::stock.create-edit')->with([
        "edit" => false,
        'product' => $product,
      ]);

    } catch (Exception $e) {
      return redirect()->route('pages-misc-error');
    }
  }

  public function store(CreateStockRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      $stock = Stock::create($request->validated());
      $stock->update([
        'batch_number' => $this->generateBatchNumber(Type::MANUAL, auth()->id())
      ]);

      return redirect()->route('stocks.index', $request->product_id)
        ->with('success', __('app.created_successfully', ['name' => __('products::app.stock')]));

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
      $stock = Stock::findOrFail($id);
      $product = $stock->product;

      return view('products::stock.create-edit')->with([
        'edit' => true,
        'model' => $stock,
        'product' => $product,
      ]);
    } catch (Exception $e) {
      return redirect()->route('pages-misc-error');
    }
  }

  public function update(EditStockRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Stock::findOrFail($id);
      $model->update($request->validated());


      return redirect()->route('stocks.index', $model->product_id)
        ->with('success', __('app.updated_successfully', ['name' => __('products::app.stock')]));

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
      $model = Stock::findOrFail($id);
      $product_id = $model->product_id;
      $model->delete();


      return redirect()->route('stocks.index', $product_id)
        ->with('success', __('app.deleted_successfully', ['name' => __('products::app.stock')]));

    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
