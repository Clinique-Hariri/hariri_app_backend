<?php

namespace Modules\Purchases\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Products\Models\Stock;
use App\Http\Controllers\Controller;
use Modules\Products\Constants\Type;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use Modules\Purchases\Models\Purchase;
use Modules\Purchases\Models\Supplier;
use Modules\Purchases\Resources\ProductResource;
use Modules\Purchases\Datatables\PurchaseDatatable;
use Modules\Purchases\Http\Requests\CreatePurchaseRequest;
use Modules\Purchases\Traits\BatchNumberGeneratorTrait;

class PurchaseController extends Controller
{

  use BatchNumberGeneratorTrait;
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }

        $datatable = new PurchaseDatatable();
        if ($request->wantsJson()) {
            return $datatable->datatables($request);
        }
        return view("purchases::purchase.index")->with([
            "columns" => $datatable::columns(),
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }

        $purchase = Purchase::findOrFail($id);

        return view("purchases::purchase.show")->with([
            "purchase" => $purchase,
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        $suppliers = Supplier::all();
        $products = ProductResource::collection(Product::all());
        return view("purchases::purchase.create")->with(["suppliers" => $suppliers, "products" => $products]);
    }

    public function store(CreatePurchaseRequest $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }

        try {
            $purchase = Purchase::create(['supplier_id' => $request->supplier_id]);

            // Create purchase items
            foreach ($request->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                Stock::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => Type::PURCHASE,
                    'purchase_price' => $item['unit_price'],
                    'batch_number' => $this->generateBatchNumber(Type::PURCHASE, $purchase->id)
                ]);
            }

            $purchase->total_amount = $purchase->items()->sum('total_price');
            $purchase->save();

            return redirect()->route('purchases.index')->with('success', __('app.created_successfully', ['name' => __('purchases::app.purchase')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        try {
            $model = Purchase::findOrFail($id);
            $model->delete();
            return redirect()->route('purchases.index')->with('success', __('app.deleted_successfully', ['name' => __('purchases::app.purchase')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
