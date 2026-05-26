<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Products\Models\Unit;
use Modules\Products\Models\Stock;
use App\Http\Controllers\Controller;
use Modules\Orders\Models\OrderItem;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use Modules\Categories\Models\Category;
use Modules\Orders\Constants\OrderStatus;
use Modules\Products\Datatables\ProductDatatable;
use Modules\Products\Http\Requests\EditProductRequest;
use Modules\Products\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new ProductDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }

    $categories = Category::all()->pluck('name', 'id')->toArray();
    $units = Unit::all()->pluck('name', 'id')->toArray();
    $counts = [
'total' => Product::count(),
'quantity' => Stock::sum('quantity'),
'discounted' => Product::whereHas('discount')->count(),
'in_transit' => OrderItem::whereHas('order', function($query){
$query->where('status', OrderStatus::ONGOING);
})->sum('quantity')
];

    return view("products::product/index")->with([
      "columns" => $datatable::columns(),
      "categories" => $categories,
      'units' => $units,
      'counts' => $counts
    ]);
  }

  // In the create method, add all products to the view
  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    $categories = Category::all();
    $units = Unit::all();
    $products = Product::all(); // Add this line
    return view("products::product/create-edit")->with([
      "edit" => false,
      "categories" => $categories,
      "units" => $units,
      "products" => $products // Add this line
    ]);
  }

  // In the edit method, add all products to the view
  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    $categories = Category::all();
    $units = Unit::all();
    $products = Product::all(); // Add this line
    return view("products::product/create-edit")->with([
      "edit" => true,
      "model" => Product::with('alternatives')->findOrFail($id), // Update this line
      "categories" => $categories,
      "units" => $units,
      "products" => $products // Add this line
    ]);
  }

  // In the store method, add handling for alternative products
  public function store(CreateProductRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Product::create($request->validated());

      // Handle alternative products
      if ($request->has('alternative_products')) {
          foreach ($request->alternative_products as $alternateProductId) {
              $model->alternatives()->create([
                  'alternate_product_id' => $alternateProductId
              ]);
          }
      }

      if($request->has('discounted_price')){
        $discount = (1 - $request->discounted_price / $model->price) * 100;
        $model->discounts()->create(['discount' => $discount, 'start_date' => now()]);
      }

      if($request->has('purchase_price', 'quantity')){
        $model->stocks()->create($request->only('purchase_price', 'quantity'));
      }

      if ($request->hasFile(Product::Image)) {
        storeWebPWithSpatie($model, $request->file(Product::Image), Product::Image);
      }

/*   if ($request->hasFile(Product::Gallery)) {
        foreach ($request->file(Product::Gallery) as $image) {
          storeWebPWithSpatie($model, $image, Product::Gallery);
        }
      } */

      return redirect()->route('products.index')->with('success', __('app.created_successfully', ['name' => __('products::app.product')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  // In the update method, add handling for alternative products
  public function update(EditProductRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Product::findOrFail($id);
      $model->update($request->validated());

      // Handle alternative products
      $model->alternatives()->delete(); // Remove existing alternatives
      if ($request->has('alternative_products')) {
          foreach ($request->alternative_products as $alternateProductId) {
              $model->alternatives()->create([
                  'alternate_product_id' => $alternateProductId
              ]);
          }
      }

      if ($request->hasFile(Product::Image)) {
        $model->clearMediaCollection(Product::Image);
        storeWebPWithSpatie($model, $request->file(Product::Image), Product::Image);
      }

      if ($request->hasFile(Product::Gallery)) {
        if ($request->input('clear_gallery', false)) {
          $model->clearMediaCollection(Product::Gallery);
        }

        foreach ($request->file(Product::Gallery) as $image) {
          storeWebPWithSpatie($model, $image, Product::Gallery);
        }
      }

      return redirect()->route('products.index')->with('success', __('app.updated_successfully', ['name' => __('products::app.product')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Product::findOrFail($id);
      $model->delete();
      return redirect()->route('products.index')->with('success', __('app.deleted_successfully', ['name' => __('products::app.product')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
