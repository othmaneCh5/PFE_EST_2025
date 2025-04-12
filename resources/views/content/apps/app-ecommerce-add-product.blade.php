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
    <!-- 
         Instead of absolute positioning your filter,
         place it in the HTML and then move it into the DataTables search container.
         In this example, we leave it here so that we can later append it.
    -->
    {{-- <select id="category-filter" class="form-select" style="width: 200px; font-size: 14px;">
      <option value="">All Categories</option>
      @foreach ($categories as $category)
        <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
      @endforeach
    </select> --}}

<div class="card">
  <div class="card-datatable table-responsive">
    <table class="table border-top datatables-products" id="productTable">
      <thead>
        <tr>
          <th class="text-nowrap">ID Produit</th>  <!-- 2 -->
          <th>Produit</th>                         <!-- 3 -->
          <th>Description</th>                     <!-- 4 -->
          <th>Prix</th>                            <!-- 5 -->
          <th>Stock</th>                           <!-- 6 -->
          <th style="width:100px;">Quantité</th>     <!-- 7 -->
          <th>Ajouter</th>                         <!-- 8 -->
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
        <tr data-stock="{{ $product->quantity }}" data-category="{{ strtolower($product->category->name) }}">

          <!-- 2) ID -->
          <td>{{ $product->id }}</td>

          <!-- 3) Product Image -->
          <td>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" height="50">
            {{ $product->name }}
          </td>

          <!-- 4) Description (shortened) -->
          <td>{{ Str::limit($product->description, 50) }}</td>

          <!-- 5) Price -->
          <td>${{ $product->price }}</td>

          <!-- 6) Stock with a CSS class -->
          <td class="stock-value">{{ $product->quantity }}</td>

          <!-- 7) Quantity input with + / - buttons -->
          <td>
            <div class="d-flex align-items-center">
              <button class="btn btn-sm btn-outline-primary me-2 minus-btn" data-product-id="{{ $product->id }}">-</button>
              <input type="number"
                     class="form-control form-control-sm quantity-input"
                     data-product-id="{{ $product->id }}"
                     value="1" min="1"
                     style="width:60px;" />
              <button class="btn btn-sm btn-outline-primary ms-2 plus-btn" data-product-id="{{ $product->id }}">+</button>
            </div>
          </td>

          <!-- 8) Attach Product Form with hidden quantity -->
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
  // ------------------------------------------------------------------
  // 0) Pass categories from backend (Blade) to JavaScript
  // ------------------------------------------------------------------
  // This assumes $categories is an array of objects with a "name" property.
  const categories = @json($categories);

  // Dynamically create the select element for the category filter.
  let $categoryFilter = $('<select id="category-filter" class="form-select" style="width: 200px; font-size: 14px;"></select>');
  $categoryFilter.append($('<option>', { value: '', text: 'All Categories' }));
  categories.forEach(function(category) {
    // Using toLowerCase() for case-insensitive matching.
    $categoryFilter.append($('<option>', { value: category.name.toLowerCase(), text: category.name }));
  });

  //-----------------------------------------------------------
  // 1) Quantity + / - Buttons (Existing Code)
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
  // 2) Initialize DataTable with Search and Custom Title
  //-----------------------------------------------------------
  const dt = $('.datatables-products').DataTable({
    dom:
      // Build a header row with two containers: left for search (and our category filter) and right for title.
      '<"d-flex justify-content-between align-items-center mb-3"' +
        '<"dt-search"f>' +
        '<"dt-title text-end">' +
      '>t' +
      // Info + Pagination row.
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
      // Make columns 0 and 1 non-searchable and non-orderable.
      { targets: [0,1], searchable: false, orderable: false },
    ],
    order: [[2, 'desc']], // Adjust the sorting column as needed.
    initComplete: function () {
      // Insert the page title into the right-side container.
      $('.dt-title').html(`
        <h5 class="m-0 me-10">
          Ajouter un produit à la commande #{{ $commande->id }}
        </h5>
      `);

      // --- Adjust the search container and append the category filter ---
      // Our DataTables dom puts the search field into a container like:
      // <div class="dt-search"><div id="productTable_filter" class="dataTables_filter"> ... </div></div>
      // We want to have both the search input and the category filter in a row.
      let $searchContainer = $('.dt-search .dataTables_filter');
      // Apply flex layout so its children line up horizontally.
      $searchContainer.css({
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-start'
      });
      // Remove any bottom margin on the search label.
      $searchContainer.find('label').css({ marginBottom: 0 });
      // Add a small left margin on the filter so it doesn’t stick too close to the search.
      $categoryFilter.css({ marginLeft: '10px' });
      // Append the category filter into the search container.
      $searchContainer.append($categoryFilter);
    }
  });

  // Optional: Remove the "sm" classes added by default for a more standard look.
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  //-----------------------------------------------------------
  // 3) Validate Available Stock on "Ajouter" Button Click (Form Submit)
  //-----------------------------------------------------------
  document.querySelectorAll('.attach-product-form').forEach(form => {
    form.addEventListener('submit', function(event) {
      event.preventDefault(); // Stop submission until we validate

      // Get the current product row and its available stock (stored in a data attribute)
      const row = form.closest('tr');
      let availableStock = parseInt(row.getAttribute('data-stock'));

      // Retrieve the product ID and the selected quantity from the row
      const productId = form.querySelector('input[name="product_id"]').value;
      let quantityInput = row.querySelector(`.quantity-input[data-product-id='${productId}']`);
      let selectedQuantity = parseInt(quantityInput.value);

      // Alert and halt if selected quantity exceeds available stock
      if (selectedQuantity > availableStock) {
        alert('Not enough quantity in stock!');
        return false;
      } else {
        // Optionally update the stock in the UI
        let newStock = availableStock - selectedQuantity;
        row.setAttribute('data-stock', newStock);
        row.querySelector('.stock-value').innerText = newStock;

        // Proceed with form submission
        form.submit();
      }
    });
  });

  //-----------------------------------------------------------
  // 4) Implement Custom DataTables Filter for Category
  //-----------------------------------------------------------
  // Add a custom filter that filters rows based on the row's data-category attribute.
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const selectedCategory = $('#category-filter').val();
    // Get the row node using the DataTables API.
    const row = dt.row(dataIndex).node();
    const rowCategory = $(row).data('category');

    // If "All Categories" is selected (empty value) or the row matches, show the row.
    return (!selectedCategory || rowCategory === selectedCategory);
  });

  // Redraw the table whenever the category filter value changes.
  $('#category-filter').on('change', function() {
    dt.draw();
  });
});
</script>
@endpush

