@extends('layouts.contentNavbarLayout')

@section('title', __('orders::app.order_details'))

@section('vendor-style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <style>
        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        #map {
            height: 200px;
            width: 100%;
            border-radius: 0.375rem;
        }

        .image-preview {
            max-width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .image-preview:hover {
            transform: scale(1.05);
        }

        .view-image-btn {
            margin-top: 0.5rem;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h5 class="mb-1 mt-3">
                @lang('orders::app.order') #{{ $order->id }}
                <span class="badge bg-label-{{ \Modules\Orders\Constants\OrderStatus::colors()[$order->status] }} me-2 ms-2">
                    {{ \Modules\Orders\Constants\OrderStatus::all(true)[$order->status] }}
                </span>
                @if ($order->with_prescription)
                    <span class="badge bg-label-info">@lang('orders::app.with_prescription')</span>
                @endif
            </h5>
            <p class="text-muted">{{ $order->created_at->format('M d, Y, H:i') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-2">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_ORDERS)
                <a href="{{ route('orders.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
                <button class="btn btn-label-danger delete-order" data-order-id="{{ $order->id }}"><i
                        class="bx bx-trash me-1"></i>@lang('orders::app.delete_order')</button>
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                    <i class="bx bx-cart me-1"></i>{{ __('orders::app.update_cart') }}
                </a>
            @endpermission

        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <!-- Order Items Table -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">@lang('orders::app.order_items')</h5>
                    <div>
                        @if ($order->getFirstMediaUrl(Modules\Orders\Models\Order::INVOICE))
                            <a href="{{ $order->getFirstMediaUrl(Modules\Orders\Models\Order::INVOICE) }}" target="_blank"
                                class="btn btn-sm btn-primary">
                                <i class="bx bx-printer me-1"></i> @lang('orders::app.print_invoice')
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    @if ($order->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('orders::app.product')</th>
                                        <th class="text-center" style="width: 100px">@lang('orders::app.quantity')</th>
                                        <th class="text-end" style="width: 150px">@lang('orders::app.unit_price')</th>
                                        <th class="text-end" style="width: 150px">@lang('orders::app.total_price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach ($order->items as $item)
                                        @php
                                            $itemTotal = $item->quantity * $item->unit_price;
                                            $totalAmount += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center user-name">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar avatar me-2 rounded-2 bg-label-secondary">
                                                            @if ($item->product && $item->product->getFirstMediaUrl('image'))
                                                                <img src="{{ $item->product->getFirstMediaUrl('image') }}"
                                                                    alt="{{ $item->product->name }}" class="rounded-2">
                                                            @else
                                                                <i class="bx bx-image-alt text-muted"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('products.edit', $item->product_id) }}"
                                                            class="text-body text-truncate">
                                                            <span
                                                                class="fw-medium">{{ $item->product ? $item->product->name : __('orders::app.product_deleted') }}</span>
                                                        </a>

                                                        <small class="text-muted">{{ $item->product?->unit_count }}
                                                            {{ $item->product?->unit->name }}</small>

                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    if ($item->quantity <= $item->product->avaialable_quantity) {
                                                        $color = 'teal';
                                                    } elseif ($item->quantity <= $item->product->stock_quantity) {
                                                        $color = 'warning';
                                                    } else {
                                                        $color = 'danger';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge bg-label-{{ $color }}">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($item->unit_price, 2) }}
                                                @lang('app.DZD')</td>
                                            <td class="text-end fw-semibold">{{ number_format($itemTotal, 2) }}
                                                @lang('app.DZD')</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold">@lang('orders::app.subtotal'):</td>
                                        <td class="text-end fw-semibold">{{ number_format($order->purchase_amount, 2) }}
                                            @lang('app.DZD')
                                        </td>
                                    </tr>
                                    @if ($order->discount_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end text-primary fw-semibold">@lang('orders::app.discount'):
                                            </td>
                                            <td class="text-end text-primary fw-semibold">
                                                -{{ number_format($order->discount_amount, 2) }} @lang('app.DZD')</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold">@lang('orders::app.delivery'):</td>
                                        <td class="text-end fw-semibold">{{ number_format($order->delivery_amount, 2) }}
                                            @lang('app.DZD')
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-semibold">@lang('orders::app.total'):</td>
                                        <td class="text-end fw-semibold">{{ number_format($order->total_amount, 2) }}
                                            @lang('app.DZD')</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-cart">
                            <i class="bx bx-cart text-muted"></i>
                            <p class="text-muted mb-0">@lang('orders::app.no_items_in_cart')</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Information with Map -->
            @if ($order->location)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title m-0">@lang('orders::app.delivery_info')</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <i class="bx bx-map me-1"></i>
                            @lang('orders::app.delivery_address'): {{ $order->location?->address ?? '-' }}
                        </p>

                        <p class="mb-3">
                            <i class="bx bx-time-five me-1"></i>
                            @lang('orders::app.delivery_time'): {{ $order->delivery_time?->format('M d, Y H:i') ?? '-' }}
                        </p>

                        @if ($order->latitude && $order->longitude)
                            <div id="map" class="mb-2" style="height: 300px; width: 500px; margin: 0 auto;"></div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Order Information Sidebar -->
        <div class="col-12 col-lg-4">
            <!-- Customer Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">@lang('orders::app.customer_details')</h6>
                </div>
                <div class="card-body">
                    @if ($order->user)
                        <div class="d-flex justify-content-start align-items-center mb-4">
                            <div class="avatar me-2">
                                <img src="{{ $order->user->avatar_url ?? asset('assets/img/avatars/1.png') }}"
                                    alt="Avatar" class="rounded-circle">
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">{{ $order->user->fullname }}</h6>
                                <small class="text-muted">@lang('orders::app.customer_id'): #{{ $order->user->id }}</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-center mb-4">
                            <span
                                class="avatar rounded-circle bg-label-success me-2 d-flex align-items-center justify-content-center">
                                <i class="bx bx-cart-alt bx-sm lh-sm"></i>
                            </span>
                            <h6 class="text-body text-nowrap mb-0">{{ $order->user->orders()->count() }}
                                @lang('orders::app.orders')
                            </h6>
                        </div>
                        <div class="mb-3">
                            <h6>@lang('orders::app.contact_info')</h6>
                            <p class="mb-1">@lang('orders::app.email'): {{ $order->user->email }}</p>
                            <p class="mb-0">@lang('orders::app.phone'): {{ $order->phone }}</p>
                        </div>
                    @else
                        <p class="text-muted">@lang('orders::app.customer_not_found')</p>
                    @endif
                </div>
            </div>

            <!-- Order Image Display with Viewer.js -->
            @if ($order->getFirstMediaUrl('image'))
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title m-0">@lang('orders::app.order_image')</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <!-- Hidden image for Viewer.js -->
                            <div id="order-images" style="display: none;">
                                <img src="{{ $order->getFirstMediaUrl('image') }}" alt="Order Image">
                            </div>

                            <!-- Preview thumbnail -->
                            <img src="{{ $order->getFirstMediaUrl('image') }}" alt="Order Image Preview"
                                class="image-preview me-3">

                            <!-- View button -->
                            <div>
                                <h6 class="mb-1">@lang('orders::app.order_attachment')</h6>
                                <p class="text-muted mb-2 small">@lang('orders::app.click_to_view_full_image')</p>
                                <button type="button" class="btn btn-outline-primary btn-sm view-image-btn"
                                    id="viewImageBtn">
                                    <i class="bx bx-show me-1"></i>
                                    @lang('orders::app.view_image')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Driver Information -->
            @if ($order->driver)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title m-0">@lang('orders::app.driver_info')</h6>
                        <a href="https://wa.me/{{ $order->driver->phone }}" target="_blank"
                            class="btn btn-sm btn-label-success">
                            <i class="bx bxl-whatsapp me-1"></i>WhatsApp
                        </a>

                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if ($order->driver->getFirstMediaUrl(\Modules\Orders\Models\Driver::IMAGE))
                                <img src="{{ $order->driver->getFirstMediaUrl(\Modules\Orders\Models\Driver::IMAGE) }}"
                                    alt="Driver" class="rounded-circle me-2"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $order->driver->name }}</h6>
                                <small class="text-muted">{{ $order->driver->phone }}</small>
                            </div>
                        </div>
                        {{--               <div class="d-flex align-items-center gap-2">
                            <span
                                class="badge bg-label-{{ \Modules\Orders\Constants\DriverStatus::colors()[$order->driver->status] }}">
                                {{ \Modules\Orders\Constants\DriverStatus::all(true)[$order->driver->status] }}
                            </span>
                        </div> --}}
                    </div>
                </div>
            @endif

            <!-- Order Summary -->
            {{-- <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title m-0">@lang('orders::app.order_summary')</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>@lang('orders::app.order_type'):</strong> {{ ucfirst($order->type) }}
                    </div>
                    @if ($order->delivery_time)
                    <div class="mb-2">
                        <strong>@lang('orders::app.delivery_time'):</strong> {{ $order->delivery_time->format('M d, Y H:i')
                        }}
                    </div>
                    @endif
                    @if ($order->note)
                    <div class="mb-2">
                        <strong>@lang('orders::app.note'):</strong>
                        <p class="text-muted mb-0">{{ $order->note }}</p>
                    </div>
                    @endif
                    @if ($order->coupon)
                    <div class="mb-2">
                        <strong>@lang('orders::app.coupon'):</strong> {{ $order->coupon->code }}
                    </div>
                    @endif
                </div>
            </div> --}}
        </div>
    </div>

    <x-modal.confirmation id="delete-modal" title="{{ __('app.delete') }}"
        action="{{ route('orders.destroy', ':id') }}" method="DELETE" inputs='' theme="danger" />

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {

            $('.delete-order').click(function() {
                const Id = $(this).data('order-id');
                const form = $('#delete-modal form');
                form.attr('action', form.attr('action').replace(':id', Id));
                $('#delete-modal').modal('show');
            });

            // Initialize Viewer.js for order images
            @if ($order->getFirstMediaUrl('image'))
                const orderImagesContainer = document.getElementById('order-images');
                const viewer = new Viewer(orderImagesContainer, {
                    inline: false,
                    viewed() {
                        viewer.zoomTo(1);
                    },
                    toolbar: {
                        zoomIn: 1,
                        zoomOut: 1,
                        oneToOne: 1,
                        reset: 1,
                        prev: 0,
                        play: 0,
                        next: 0,
                        rotateLeft: 1,
                        rotateRight: 1,
                        flipHorizontal: 1,
                        flipVertical: 1
                    },
                    title: [1, (image, imageData) =>
                        `${imageData.alt} (${imageData.naturalWidth} × ${imageData.naturalHeight})`
                    ]
                });

                // View image button click
                $('#viewImageBtn').click(function() {
                    viewer.show();
                });

                // Also allow clicking on preview image
                $('.image-preview').click(function() {
                    viewer.show();
                });
            @endif

            // Initialize map if coordinates are available
            @if ($order->latitude && $order->longitude)
                var map = L.map('map').setView([{{ $order->latitude }}, {{ $order->longitude }}], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([{{ $order->latitude }}, {{ $order->longitude }}])
                    .addTo(map)
                    .bindPopup('{{ $order->location->address ?? 'Delivery Location' }}')
                    .openPopup();
            @endif

        });
    </script>
@endsection
