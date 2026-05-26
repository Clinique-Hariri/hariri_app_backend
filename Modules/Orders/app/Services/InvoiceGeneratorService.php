<?php

namespace Modules\Orders\Services;


use Mpdf\Mpdf;
use Modules\Orders\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Modules\Orders\Resources\web\OrderResource;
use Modules\Orders\Resources\web\UserResource;
use Modules\Orders\Resources\web\OrderItemResource;

class InvoiceGeneratorService
{

  private $order;
  private $currency;
  private $locale;

  public function __construct(
    Order $order
  ) {

    $this->order = $order;
    $this->currency = __('app.DZD');
    $this->locale = session('locale');
  }

  public function generatePdf()
  {

    try {
      $isRTL = $this->locale === 'ar';
      //$isRTL = false;
      // Create the mPDF instance with RTL support if needed
      $order = new OrderResource($this->order)->toArray(request());
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
      if ($this->order->location) {
        $qr_code = QrCode::size(100)
          //->merge(public_path('assets/img/icons/brands/google-maps.png'),.3 , true)
          ->style("round")
          ->generate($this->order->location_url);
        // Removing the xml tag
        $qr_code = substr($qr_code, 39);
      }

      // Render the Blade template
      $html = View::make('orders::invoice.pdf', [
        'seller' => $seller,
        'buyer' => $buyer,
        'driver' => $driver,
        'items' => $items,
        'order' => $order,
        'qr_code' => $qr_code ?? '',
        'currency' => $this->currency,
        'isRTL' => $isRTL,
        'locale' => $this->locale
      ])->render();

      //$mpdf->autoLangToFont = true;

      $mpdf->WriteHTML($html);

      $pdfContent = $mpdf->Output('', 'S');

      $media = $this->order->addMediaFromString($pdfContent)
        ->usingFileName(uuid_create() . '.pdf')
        ->usingName('Invoice PDF')
        ->toMediaCollection(Order::INVOICE);

      return $media;

    } catch (\Exception $e) {
      dd('Media Library Error: ' . $e->getMessage(), $e->getTraceAsString());
    }
  }
}
