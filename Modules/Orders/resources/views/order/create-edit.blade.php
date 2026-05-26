@extends('layouts/contentNavbarLayout')

@section('title', __('orders::app.pos'))

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('content')
 <!-- Header Actions -->
 <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
  <div class="d-flex flex-column justify-content-center">
      @if(isset($order))
          <h4 class="mb-1 mt-3">@lang('orders::app.update_cart')</h4>
          <p class="text-muted">@lang('orders::app.order') #{{ $order->id }}</p>
      @else
          <h4 class="mb-1 mt-3">@lang('orders::app.create_order')</h4>
          <p class="text-muted">@lang('orders::app.create_new_order')</p>
      @endif
  </div>
  <div class="d-flex align-content-center flex-wrap gap-3">
      @if(isset($order))
          <a href="{{ route('orders.show', $order->id) }}" class="btn btn-label-secondary">
              <i class="bx bx-arrow-back me-1"></i>@lang('app.back')
          </a>
      @else
          <a href="{{ route('orders.index') }}" class="btn btn-label-secondary">
              <i class="bx bx-arrow-back me-1"></i>@lang('app.back')
          </a>
      @endif
  </div>
</div>

<!-- Barcode Scanner (Outside Form) -->
<div class="row mb-4">
  <!-- Barcode Scanner / Product Search Card -->
  <div class="col-md-8">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title m-0">{{ __('orders::app.barcode_scanner_product_search') }}</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('orders::app.scan_barcode') }}</label>
            <input type="text" id="barcode-input" class="form-control" placeholder="{{ __('orders::app.type_barcode') }}" autofocus>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('orders::app.quick_add_product') }}</label>
            <select id="quick-add-product" class="form-select select2">
              <option value="">{{ __('orders::app.select_product_to_add') }}</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->final_price ?? 0 }}" data-barcode="{{ $product->barcode ?? '' }}">
                  {{ $product->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Amount Card -->
  <div class="col-md-4">
    <div class="card h-100 bg-label-primary">
      <div class="card-header">
        <h5 class="card-title m-0 text-center">{{ __('orders::app.total_amount') }}</h5>
      </div>
      <div class="card-body text-center d-flex flex-column justify-content-center">
        <p class="display-4 mb-2" id="total-display">0.00 {{ __('app.DZD') }}</p>
        <p class="text-muted mb-0" id="item-count">0 {{ __('orders::app.items') }}</p>
      </div>
    </div>
  </div>
</div>
@if (isset($order))
<form action="{{ route('orders.update', $order->id) }}" method="POST" id="order-form">
  @method('PATCH')
@else
<form action="{{ route('orders.store') }}" method="POST" id="order-form">
@endif

@csrf


  <div class="row">
    <!-- Left Column - Items -->
    <div class="col-xl-8">

      <!-- Items List -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title m-0">{{ __('orders::app.order_items') }}</h5>
        </div>
        <div class="card-body">
          <div id="items-container">
            <div class="text-center py-5" id="empty-state">
              <i class="bx bx-package bx-lg text-muted mb-3"></i>
              <p class="text-muted">{{ __('orders::app.scan_barcode_or_select_product_to_start') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Actions -->
    <div class="col-xl-4">
      <!-- Action Buttons -->
      <div class="card">
        <div class="card-body d-grid gap-2">
          <button type="button" class="btn btn-label-danger" onclick="clearAll()">
            <i class="bx bx-trash me-1"></i>{{ __('orders::app.clear_all_items') }}
          </button>
          <button type="submit" class="btn btn-label-success">
            @if (isset($order))
            <i class="bx bx-check me-1"></i>{{ __('orders::app.update_cart') }}
            @else
            <i class="bx bx-check me-1"></i>{{ __('orders::app.create_cart') }}
            @endif
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
  $('.select2').select2();

  // Initialize items with existing order items
  let items = @json(isset($items) ? $items : []);
  let products = @json($products->keyBy('id'));

  // Render existing items on page load
  renderItems();
  updateTotal();

  // Barcode input handler
  $('#barcode-input').on('keypress', function(e) {
      if (e.which === 13) { // Enter key
          const query = $(this).val().trim();
          if (query) {
              addProductBySearch(query);
              $(this).val('');
          }
      }
  });

  // Quick add product handler
  $('#quick-add-product').on('change', function() {
      const productId = $(this).val();
      if (productId) {
          addProduct(productId);
          $(this).val(null).trigger('change');
      }
  });

  function addProductBySearch(query) {
      // Search by barcode or name
      const product = Object.values(products).find(p =>
          (p.barcode && p.barcode.toLowerCase().includes(query.toLowerCase())) ||
          p.name.toLowerCase().includes(query.toLowerCase())
      );

      if (product) {
          addProduct(product.id);
      } else {
          Swal.fire({
              icon: 'warning',
              title: '{{ __('orders::app.product_not_found') }}',
              text: query,
              confirmButtonText: '{{ __('app.ok') }}'
          });
      }
  }

  function addProduct(productId) {
      const product = products[productId];
      if (!product) return;

      // Check if item already exists
      const existingIndex = items.findIndex(item => item.product_id == productId);

      if (existingIndex !== -1) {
          // Increase quantity
          items[existingIndex].quantity += 1;
          items[existingIndex].total_price = items[existingIndex].quantity * items[existingIndex].unit_price;
      } else {
          // Add new item
          items.push({
              product_id: productId,
              name: product.name,
              barcode: product.barcode || '',
              unit_price: parseFloat(product.final_price || 0),
              quantity: 1,
              total_price: parseFloat(product.final_price || 0)
          });
      }

      renderItems();
      updateTotal();
  }

  function renderItems() {
      const container = $('#items-container');
      let emptyState = $('#empty-state');

      if (items.length === 0) {
          // Remove table if exists
          container.find('.table-responsive').remove();

          // Ensure empty state exists and is visible
          if (emptyState.length === 0) {
              container.append(`
                  <div class="text-center py-5" id="empty-state">
                      <i class="bx bx-package bx-lg text-muted mb-3"></i>
                      <p class="text-muted">{{ __('orders::app.scan_barcode_or_select_product_to_start') }}</p>
                  </div>
              `);
          } else {
              emptyState.show();
          }
          return;
      }

      // Hide empty state if it exists
      emptyState.hide();

      // Remove existing table
      container.find('.table-responsive').remove();

      let html = '<div class="table-responsive"><table class="table table-hover">';
      html += '<thead><tr><th>{{ __('orders::app.product') }}</th><th width="130">{{ __('orders::app.quantity') }}</th><th width="130">{{ __('orders::app.unit_price') }}</th><th width="130">{{ __('orders::app.total_price') }}</th><th width="80">{{ __('orders::app.action') }}</th></tr></thead><tbody>';

      items.forEach((item, index) => {
          html += `
              <tr>
                  <td>
                      <div class="fw-semibold">${item.name}</div>
                      ${item.barcode ? `<small class="text-muted">{{ __('orders::app.barcode') }}: ${item.barcode}</small>` : ''}
                      <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                      <input type="hidden" name="items[${index}][unit_price]" value="${item.unit_price}">
                      <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                  </td>
                  <td>
                      <div class="input-group input-group-sm">
                          <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(${index}, -1)">-</button>
                          <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                          <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(${index}, 1)">+</button>
                      </div>
                  </td>
                  <td>
                      <div class="input-group input-group-sm">
                          <input type="number" step="0.01" min="0" class="form-control" value="${item.unit_price.toFixed(2)}"
                                 onchange="updateUnitPrice(${index}, this.value)">
                      </div>
                  </td>
                  <td class="text-end fw-bold">${item.total_price.toFixed(2)} {{ __('app.DZD') }}</td>
                  <td>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">
                          <i class="bx bx-trash"></i>
                      </button>
                  </td>
              </tr>
          `;
      });

      html += '</tbody></table></div>';
      container.append(html);
  }

  window.changeQuantity = function(index, change) {
      if (items[index]) {
          items[index].quantity = Math.max(1, items[index].quantity + change);
          items[index].total_price = items[index].quantity * items[index].unit_price;
          renderItems();
          updateTotal();
      }
  };

  window.updateUnitPrice = function(index, newPrice) {
      if (items[index]) {
          const price = parseFloat(newPrice) || 0;
          items[index].unit_price = price;
          items[index].total_price = items[index].quantity * price;
          renderItems();
          updateTotal();
      }
  };

  window.removeItem = function(index) {
      Swal.fire({
          title: '{{ __('app.are_you_sure') }}',
          text: '{{ __('orders::app.remove_this_item') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('app.remove') }}',
          cancelButtonText: '{{ __('app.cancel') }}'
      }).then((result) => {
          if (result.isConfirmed) {
              items.splice(index, 1);
              renderItems();
              updateTotal();

              if (items.length === 0) {
                  Swal.fire({
                      icon: 'info',
                      title: '{{ __('orders::app.cart_empty') }}',
                      text: '{{ __('orders::app.cart_is_now_empty') }}',
                      timer: 2000,
                      showConfirmButton: false
                  });
              }
          }
      });
  };

  window.clearAll = function() {
      if (items.length === 0) {
          Swal.fire({
              icon: 'info',
              title: '{{ __('orders::app.cart_already_empty') }}',
              text: '{{ __('orders::app.no_items_to_clear') }}',
              timer: 2000,
              showConfirmButton: false
          });
          return;
      }

      Swal.fire({
          title: '{{ __('app.are_you_sure') }}',
          text: '{{ __('orders::app.clear_all_items_confirm') }}',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: '{{ __('app.clear_all') }}',
          cancelButtonText: '{{ __('app.cancel') }}'
      }).then((result) => {
          if (result.isConfirmed) {
              items = [];
              renderItems();
              updateTotal();

              Swal.fire({
                  icon: 'success',
                  title: '{{ __('orders::app.cart_cleared') }}',
                  text: '{{ __('orders::app.all_items_removed') }}',
                  timer: 2000,
                  showConfirmButton: false
              });
          }
      });
  };

  function updateTotal() {
      const total = items.reduce((sum, item) => sum + item.total_price, 0);
      $('#total-display').text(total.toFixed(2) + ' {{ __('app.DZD') }}');
      $('#item-count').text(items.length + ' ' + (items.length !== 1 ? '{{ __('orders::app.items') }}' : '{{ __('orders::app.item') }}'));
  }

  // Form submission
  $('#order-form').on('submit', function(e) {
      if (items.length === 0) {
          e.preventDefault();
          Swal.fire({
              icon: 'warning',
              title: '{{ __('orders::app.no_items') }}',
              text: '{{ __('orders::app.please_add_at_least_one_item') }}',
              confirmButtonText: '{{ __('app.ok') }}'
          });
          return false;
      }

      return true;
  });

  // Focus barcode input when page loads
  setTimeout(() => {
      $('#barcode-input').focus();
  }, 100);
});
</script>
@endsection
