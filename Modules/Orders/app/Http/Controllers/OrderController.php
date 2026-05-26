<?php

namespace Modules\Orders\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Orders\Constants\OrderType;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Orders\Models\OrderItem;
use Modules\Products\Models\Product;
use Modules\Products\Models\Stock;
use Modules\Products\Constants\Type;
use App\Support\Enum\PermissionNames;
use Modules\Products\Constants\Status;
use App\Constants\NotificationMessages;
use Modules\Orders\Constants\OrderStatus;
use App\Notifications\NewMessageNotification;
use Modules\Orders\Datatables\OrderDatatable;
use Modules\Purchases\Resources\ProductResource;
use Modules\Orders\Resources\web\OrderItemResource;
use Modules\Orders\Services\AmountCalculatorService;
use Modules\Orders\Jobs\GenerateInvoiceJob;
use Modules\Purchases\Traits\BatchNumberGeneratorTrait;

class OrderController extends Controller
{

  use BatchNumberGeneratorTrait;
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new OrderDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }

    $drivers = Driver::all()->pluck('name', 'id')->toArray();

    $counts = [];

    foreach (OrderStatus::all() as $status) {
      $counts[$status] = Order::where('status', $status)->count();
    }

    return view("orders::order/index")->with([
      "columns" => $datatable::columns(),
      "drivers" => $drivers,
      "counts" => $counts
    ]);
  }

  public function show($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $order = Order::findOrFail($id);
    /* $products = Product::with('discount')->where('status', Status::ACTIVE)
        ->whereHas('stocks', function($query) {
            $query->where('quantity', '>', 0);
        })->get(); */
    $products = Product::with('discount')->get();
    return view("orders::order.show")->with([
      "order" => $order,
      "products" => $products
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $products = ProductResource::collection(Product::with('category')->get());

    return view('orders::order.create-edit', compact('products'));
  }

  public function store(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $order = Order::create([
      'type' => OrderType::POS,
      'status' => OrderStatus::DELIVERED
    ]);

    try {
      DB::beginTransaction();

      $items = $request->items;
      $orderItems = [];
      $totalAmount = 0;

      foreach ($items as $item) {
        $unitPrice = floatval($item['unit_price']);
        $quantity = intval($item['quantity']);
        $totalPrice = $unitPrice * $quantity;
        $totalAmount += $totalPrice;

        $orderItems[] = [
          'order_id' => $order->id,
          'product_id' => $item['product_id'],
          'quantity' => $quantity,
          'unit_price' => $unitPrice,
          'final_price' => $unitPrice,
          'total_price' => $totalPrice,
          'created_at' => now(),
          'updated_at' => now()
        ];
      }


      OrderItem::insert($orderItems);

      $calculator = new AmountCalculatorService($order->items);
      $calculator->setDeliveryAmount(0);

      $order->update([
        'purchase_amount' => $calculator->getPurchaseAmount(),
        'discount_amount' => $calculator->getDiscountAmount(),
        'total_amount' => $calculator->getTotalAmount(),
      ]);

      DB::commit();
      $order->refresh();

      $items = $order->items()->with('product.stocks')->get();

      $this->updateStocks($items);

      GenerateInvoiceJob::dispatch($order);

      return redirect()->route('orders.create')
        ->with('success', __('app.updated_successfully', ['name' => __('orders::app.order')]));

    } catch (Exception $e) {
      DB::rollBack();
      return redirect()->route('orders.index')
        ->with('error', $e->getMessage());
    }
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $order = Order::findOrFail($id);

    $products = ProductResource::collection(Product::with('category')->get());
    $items = OrderItemResource::collection($order->items);

    return view('orders::order.create-edit', compact('order', 'items', 'products'));
  }

  public function update(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $order = Order::findOrFail($id);

    try {
      DB::beginTransaction();




      if ($order->status == OrderStatus::DELIVERED) {
        $oldItems = $order->items()->with('product.stocks')->get();
        $this->returnStocks($oldItems);
      }

      $order->items()->delete();

      $items = $request->items;
      $orderItems = [];
      $totalAmount = 0;

      foreach ($items as $item) {
        $unitPrice = floatval($item['unit_price']);
        $quantity = intval($item['quantity']);
        $totalPrice = $unitPrice * $quantity;
        $totalAmount += $totalPrice;

        $orderItems[] = [
          'order_id' => $order->id,
          'product_id' => $item['product_id'],
          'quantity' => $quantity,
          'unit_price' => $unitPrice,
          'final_price' => $unitPrice,
          'total_price' => $totalPrice,
          'created_at' => now(),
          'updated_at' => now()
        ];
      }

      OrderItem::insert($orderItems);

      $calculator = new AmountCalculatorService($order->items);
      $calculator->setDeliveryAmount($order->delivery_amount);

      if ($order->coupon_id) {
        $calculator->applyCoupon($order->coupon->code);
      }

      $order->update([
        'purchase_amount' => $calculator->getPurchaseAmount(),
        'discount_amount' => $calculator->getDiscountAmount(),
        'total_amount' => $calculator->getTotalAmount(),
      ]);

            $order->refresh();


      if ($order->status == OrderStatus::DELIVERED) {
        $newItems = $order->items()->with('product.stocks')->get();
        $this->updateStocks($newItems);
      }

      GenerateInvoiceJob::dispatch($order);

      DB::commit();

      return redirect()->route('orders.show', $order->id)
        ->with('success', __('app.updated_successfully', ['name' => __('orders::app.order')]));

    } catch (Exception $e) {
      DB::rollBack();
      return redirect()->route('orders.show', $order->id)
        ->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $order = Order::findOrFail($id);
    $order->delete();

    return redirect()->route('orders.index')->with('success', __('app.deleted_successfully', ['name' => __('orders::app.order')]));
  }

  public function updateStatus(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:orders,id',
      'status' => 'required|in:' . implode(',', array_keys(OrderStatus::all())),
      'driver_id' => 'required_if:status,ongoing|nullable|exists:drivers,id'
    ]);

    try {

      DB::beginTransaction();

      $order = Order::findOrFail($request->id);

      $items = $order->items()->with('product.stocks')->get();

      if ($request->status == OrderStatus::DELIVERED) {

        $this->updateStocks($items);

        if ($order->coupon_id) {
          $coupon = $order->coupon;
          $coupon->update(['usage_count' => $coupon->usage_count + 1]);
        }
      }

      $order->update([
        'status' => $request->status,
        'driver_id' => $request->driver_id ?? $order->driver_id,
      ]);

      $notification_key = match ($request->status) {
        OrderStatus::PENDING => NotificationMessages::ORDER_PENDING,
        OrderStatus::ACCEPTED => NotificationMessages::ORDER_ACCEPTED,
        OrderStatus::CANCELED => NotificationMessages::ORDER_CANCELED,
        OrderStatus::ONGOING => NotificationMessages::ORDER_ONGOING,
        OrderStatus::DELIVERED => NotificationMessages::ORDER_DELIVERED,
      };

      $order->user?->notify(new NewMessageNotification(
        key: $notification_key,
        data: ['type' => 'orders', 'order_id' => $order->id, 'order_status' => $request->status],
        channels: ['database', 'fcm'],
        replace: ['order_id' => $order->identifier]
      ));

      DB::commit();

      return redirect()->route('orders.index')->with('success', __('app.updated_successfully', ['name' => __('orders::app.order')]));

    } catch (Exception $e) {
      DB::rollBack();
      return redirect()->route('orders.index')->with('error', $e->getMessage());
    }
  }


  private function returnStocks($items)
  {
    foreach ($items as $item) {
      $product = $item->product;
      $quantityToReturn = $item->quantity;


      $oldestStock = $product->stock;

      if ($oldestStock) {

        $oldestStock->update([
          'quantity' => $oldestStock->quantity + $quantityToReturn
        ]);
      } else {

        Stock::create([
          'product_id' => $product->id,
          'quantity' => $quantityToReturn,
          'type' => Type::RETURN ,
          'purchase_price' => $item->unit_price,
          'batch_number' => $this->generateBatchNumber(Type::RETURN , $item->order_id)
        ]);
      }
    }
  }


  private function updateStocks($items)
  {
    foreach ($items as $item) {

      if ($item->quantity > $item->product->stock_quantity) {
        throw new Exception(__('orders::app.product_out_of_stock', ['product' => $item->product->name]));
      }

      while ($item->quantity > 0 && $item->product->stocks->where('quantity', '>', 0)->isNotEmpty()) {
        $currentStock = $item->product->stocks
          ->where('quantity', '>', 0)
          ->sortBy('created_at')
          ->first();

        if ($item->quantity <= $currentStock->quantity) {
          $currentStock->update(['quantity' => $currentStock->quantity - $item->quantity]);
          $item->quantity = 0;
        } else {
          $item->quantity -= $currentStock->quantity;
          $currentStock->update(['quantity' => 0]);
        }
      }
    }
  }
}
