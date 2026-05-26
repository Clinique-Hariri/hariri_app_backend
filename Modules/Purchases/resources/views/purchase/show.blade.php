@extends('layouts.contentNavbarLayout')

@section('title', __('purchases::app.purchase_details'))

@section('content')

<!-- Header Actions -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
  <div class="d-flex flex-column justify-content-center">
    <h4 class="mb-1 mt-3">@lang('purchases::app.purchase') #{{ $purchase->id }}</h4>
    <p class="text-muted">{{ $purchase->created_at->format('M d, Y, H:i') }}</p>
</div>
  <div class="d-flex align-content-center flex-wrap gap-3">
    @permission(\App\Support\Enum\PermissionNames::MANAGE_PURCHASES)
    <a href="{{ route('purchases.index') }}" class="btn btn-label-secondary">
      <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
  </a>
    @endpermission
  </div>
</div>


  <div class="row">

    <div class="col-md-8">
      <!-- Purchase Items -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title m-0">@lang('purchases::app.purchase_items')</h5>
        </div>
        <div class="card-datatable table-responsive">
          @if($purchase->items && $purchase->items->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>@lang('purchases::app.product')</th>
                    <th class="text-center" style="width: 100px">@lang('purchases::app.quantity')</th>
                    <th class="text-end" style="width: 150px">@lang('purchases::app.unit_price')</th>
                    <th class="text-end" style="width: 150px">@lang('purchases::app.total_price')</th>
                  </tr>
                </thead>
                <tbody>
                  @php $totalAmount = 0; @endphp
                  @foreach($purchase->items as $item)
                    @php
                      $itemTotal = $item->quantity * $item->unit_price;
                      $totalAmount += $itemTotal;
                    @endphp
                    <tr>
                      <td>
                        <div class="d-flex justify-content-start align-items-center user-name">
                          <div class="avatar-wrapper">
                            <div class="avatar avatar me-2 rounded-2 bg-label-secondary">
                              @if($item->product && $item->product->getFirstMediaUrl('image'))
                                <img src="{{ $item->product->getFirstMediaUrl('image') }}"
                                     alt="{{ $item->product->name }}"
                                     class="rounded-2">
                              @else
                                <i class="bx bx-image-alt text-muted"></i>
                              @endif
                            </div>
                          </div>
                          <div class="d-flex flex-column">
                            <a href="{{ route('products.edit', $item->product_id) }}" class="text-body text-truncate">
                              <span class="fw-medium">{{ $item->product ? $item->product->name : __('purchases::app.product_deleted') }}</span>
                            </a>
                            <small class="text-muted">{{ $item->product->unit_count }} {{ $item->product->unit->name }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-label-primary">{{ $item->quantity }}</span>
                      </td>
                      <td class="text-end">{{ number_format($item->unit_price, 2) }} @lang('app.DZD')</td>
                      <td class="text-end fw-semibold">{{ number_format($itemTotal, 2) }} @lang('app.DZD')</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="border-top">
                  <tr class="table-active">
                    <td colspan="3" class="text-end fw-semibold">@lang('purchases::app.total_amount'):</td>
                    <td class="text-end fw-semibold">{{ number_format($totalAmount, 2) }} @lang('app.DZD')</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          @else
            <div class="empty-cart">
              <i class="bx bx-package text-muted"></i>
              <p class="text-muted mb-0">@lang('app.no_items_found')</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Supplier Details -->
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title m-0">@lang('purchases::app.supplier_details')</h5>
        </div>
        <div class="card-body">
          @if($purchase->supplier)
            <div class="d-flex align-items-center mb-3">
              @if($purchase->supplier->getFirstMediaUrl('image'))
                <img src="{{ $purchase->supplier->getFirstMediaUrl('image') }}"
                     alt="{{ $purchase->supplier->name }}"
                     class="rounded-circle me-2"
                     style="width: 40px; height: 40px; object-fit: cover;">
              @endif
              <div>
                <h6 class="mb-0">{{ $purchase->supplier->name }}</h6>
                <small class="text-muted">{{ $purchase->supplier->phone }}</small>
              </div>
            </div>
            @if($purchase->supplier->location)
              <div class="mb-3">
                <strong>@lang('purchases::app.location'):</strong>
                <p class="mb-0">{{ $purchase->supplier->location->name ?? 'N/A' }}</p>
              </div>
            @endif
          @else
            <p class="text-muted">@lang('app.no_supplier_assigned')</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
