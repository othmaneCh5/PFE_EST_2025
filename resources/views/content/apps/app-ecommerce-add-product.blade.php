@extends('layouts/layoutMaster')

@php
  use Illuminate\Support\Str;
@endphp

@section('title', 'eCommerce Order Details - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('content')
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="table border-top datatables-products" id="productTable">
      <thead>
        <tr>
          <th></th> <!-- 1: Checkbox column -->
          <th class="text-nowrap">ID Produit</th>  <!-- 2 -->
          <th>Produit</th>                         <!-- 3 -->
          <th>Description</th>                    <!-- 4 -->
          <th>Prix</th>                           <!-- 5 -->
          <th style="width:100px;">Quantité</th>  <!-- 6 -->
          <th>Ajouter</th>                        <!-- 7 -->
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
        <tr>
          <!-- 1) Checkbox -->
          <td style="width:40px;">
            <input type="checkbox" class="form-check-input" />
          </td>

          <!-- 2) ID -->
          <td>{{ $product->id }}</td>

          <!-- 3) Product Name -->
          <td>
            {{-- <img src="{{ asset('assets/img/products/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-circle me-3" width="50" height="50"> --}}
            <img src="{{ asset('assets/img/products/woodenchair.png') }}" alt="" class="rounded-circle me-3" width="50" height="50">
            {{ $product->name }}
          </td>

          <!-- 4) Description (shortened) -->
          <td>{{ Str::limit($product->description, 50) }}</td>

          <!-- 5) Price -->
          <td>${{ $product->price }}</td>

          <!-- 6) Qte with + / - buttons -->
          <td>
            <div class="d-flex align-items-center">
              <button class="btn btn-sm btn-outline-primary me-2 minus-btn" data-product-id="{{ $product->id }}">-</button>
              <input
                type="number"
                class="form-control form-control-sm quantity-input"
                data-product-id="{{ $product->id }}"
                value="1"
                min="1"
                style="width:60px;"
              />
              <button class="btn btn-sm btn-outline-primary ms-2 plus-btn" data-product-id="{{ $product->id }}">+</button>
            </div>
          </td>

          <!-- 7) Form for Attach + hidden qte -->
          <td>
            <form action="{{ route('commande.attachProduct', $commande->id) }}" method="POST" class="attach-product-form">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}" />
              <input type="hidden" name="qte" class="hidden-qte-{{ $product->id }}" value="1" />
              <button type="submit" class="btn btn-sm btn-primary">Ajouter</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  //-----------------------------------------------------------
  // 1) Quantity + / - Buttons
  //-----------------------------------------------------------
  document.querySelectorAll('.plus-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const productId = this.dataset.productId;
      const inputEl = document.querySelector(`.quantity-input[data-product-id='${productId}']`);
      if (inputEl) {
        let newVal = parseInt(inputEl.value) + 1;
        inputEl.value = newVal;
        document.querySelector(`.hidden-qte-${productId}`).value = newVal;
      }
    });
  });

  document.querySelectorAll('.minus-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const productId = this.dataset.productId;
      const inputEl = document.querySelector(`.quantity-input[data-product-id='${productId}']`);
      if (inputEl) {
        let currVal = parseInt(inputEl.value);
        if (currVal > 1) {
          let newVal = currVal - 1;
          inputEl.value = newVal;
          document.querySelector(`.hidden-qte-${productId}`).value = newVal;
        }
      }
    });
  });

  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
      let val = parseInt(this.value);
      if (val < 1 || isNaN(val)) val = 1;
      this.value = val;
      const productId = this.dataset.productId;
      document.querySelector(`.hidden-qte-${productId}`).value = val;
    });
  });

  //-----------------------------------------------------------
  // 2) Initialize DataTable with Search (left) & Title (right)
  //-----------------------------------------------------------
  const dt = $('.datatables-products').DataTable({
    dom:
      // A single row with search on left, title on right:
      '<"d-flex justify-content-between align-items-center mb-3"' +
        '<"dt-search"f>' +
        '<"dt-title text-end">' +
      '>t' +
      // Info + Pagination row
      '<"row mx-1 mt-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
      '>',
    language: {
      sLengthMenu: '_MENU_',
      search: '',
      searchPlaceholder: 'Search Product',
      paginate: {
        next: '<i class="ti ti-chevron-right ti-sm"></i>',
        previous: '<i class="ti ti-chevron-left ti-sm"></i>'
      }
    },
    columnDefs: [
      // 0 => hidden responsive control column, 1 => checkbox
      { targets: [0,1], searchable: false, orderable: false },
    ],
    order: [[2, 'desc']], // Sort by 'ID Produit' column ascending
    initComplete: function () {
      // Insert the page title on the right
      $('.dt-title').html(`
        <h5 class="m-0 me-10">
          Ajouter un produit à la commande #{{ $commande->id }}
        </h5>
      `);
    }
  });

  // Optional styling tweaks
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
</script>
@endpush
