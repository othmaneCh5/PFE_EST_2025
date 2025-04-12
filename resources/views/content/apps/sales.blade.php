@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product List - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-product-list.js'
])
@endsection

@section('content')
<!-- Product List Widget -->

<!-- Product List Table -->
<div class="card">
  <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <h5 class="card-title m-0">Sales</h5>
</div>
  <div class="card-datatable table-responsive">
    <table id="tab" class="datatables-products table">
        <thead class="border-top">
            <tr>
                <th></th>
                <th>#</th>
                <th>client</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Qty</th>
                <th>date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td></td>
                    <td style="color:blue;">
                        #{{$sale->id}}
                    </td>
                    <td>
                        {{$sale->commande->client->name}}
                    </td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center product-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar me-4 rounded-2 bg-label-secondary">
                                    @if ($sale->product->image)
                                        <img src="{{ asset('storage/' . $sale->product->image) }}"  class="rounded-2">
                                    @else
                                        <span class="avatar-initial rounded-2 bg-label-primary">{{ substr($sale->product->name, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                              <h6 class="text-nowrap mb-0">
                                <a href="javascript:void(0);"
                                onclick="openProductModal({
                                  name: '{{ addslashes($sale->product->name) }}',
                                  description: '{{ addslashes($sale->product->description) }}',
                                  price: '{{ $sale->product->price }}',
                                  category: '{{ addslashes($sale->product->category->name ?? 'Uncategorized') }}',
                                  created_at: '{{$sale->product->created_at }}',
                                  image: '{{ asset('storage/' . $sale->product->image) }}'
                                })"
                                style="color: inherit; text-decoration: none; cursor: pointer;">
                                {{ $sale->product->name }}
                              </a>

                              </h6>
                              
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-truncate d-flex align-items-center text-heading">
                            {{ $sale->product->category ? $sale->product->category->name : 'Uncategorized' }}
                        </span>
                    </td>
                    <td>{{ $sale->price }}</td>
                    
                    <td>{{ $sale->quantity}}</td>
                    <td>
                       {{$sale->created_at->format('d/m/Y')}}
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- modal-lg for wider view -->
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">Product Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-4 align-items-center">
          <!-- Product Image -->
          <div class="col-md-4 text-center">
            <img id="view-product-image" src="" alt="Product Image" class="img-fluid rounded shadow-sm border" style="max-height: 220px;">
          </div>

          <!-- Product Info -->
          <div class="col-md-8">
            <h4 id="view-product-name" class="fw-bold mb-1">Product Name</h4>
            <p id="view-product-description" class="text-muted mb-3">Description will appear here...</p>
            
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <strong>Price:</strong> <span id="view-product-price" class="text-primary fw-bold"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <strong>Category:</strong> <span id="view-product-category"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <strong>Created at:</strong> <span id="view-product-date"></span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
