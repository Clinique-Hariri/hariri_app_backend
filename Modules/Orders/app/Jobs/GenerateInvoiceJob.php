<?php

namespace Modules\Orders\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Modules\Orders\Models\Order;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Modules\Orders\Resources\web\OrderResource;
use Modules\Orders\Resources\web\UserResource;
use Modules\Orders\Resources\web\OrderItemResource;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $isRTL = session('locale') === 'ar';
            $orderResource = new OrderResource($this->order)->toArray(request());
            $seller = [];
            $buyer = $this->order->user ? new UserResource($this->order->user)->toArray(request()) : [];
            $driver = $this->order->driver ? new UserResource($this->order->driver)->toArray(request()) : [];
            $items = OrderItemResource::collection($this->order->items)->resolve();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [210, 297], // A4 size in mm
                'default_font_size' => 12,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 30,
                'margin_bottom' => 30,
                'default_font' => $isRTL ? 'XBRiyaz' : 'arial',
                'direction' => $isRTL ? 'rtl' : 'ltr',
            ]);

            $qr_code = '';
            if ($this->order->location) {
                $qr_code = QrCode::size(100)
                    ->style("round")
                    ->generate($this->order->location_url);
                $qr_code = substr($qr_code, 39);
            }

            $html = View::make('orders::invoice.pdf', [
                'seller' => $seller,
                'buyer' => $buyer,
                'driver' => $driver,
                'items' => $items,
                'order' => $orderResource,
                'qr_code' => $qr_code,
                'currency' => __('app.DZD'),
                'isRTL' => $isRTL,
                'locale' => session('locale')
            ])->render();

            $mpdf->WriteHTML($html);

            $pdfContent = $mpdf->Output('', 'S');

            $this->order->clearMediaCollection(Order::INVOICE);
            $this->order->addMediaFromString($pdfContent)
                ->usingFileName(uuid_create() . '.pdf')
                ->usingName('Invoice PDF')
                ->toMediaCollection(Order::INVOICE);

        } catch (\Exception $e) {
            // Handle exceptions, maybe log them
        }
    }
}
