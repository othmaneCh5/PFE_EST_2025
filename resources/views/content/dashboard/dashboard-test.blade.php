@extends('layouts/layoutMaster')

@section('title', 'eCommerce Dashboard - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
  ])
@endsection


@section('content')
<div class="row g-6">
  <div class="col-xl-3 col-sm-6">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="mb-3 card-title">Average Daily Sales</h5>
        <p class="mb-0 text-body">Total Sales This Month</p>
        <h4 class="mb-0">{{$averageSales}} MAD</h4>
      </div>
      <div class="card-body px-0">
        <div id="averageDailySales"></div>
      </div>
    </div>
  </div>
  <!-- Statistics -->
  <div class="col-xl-9 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">Statistics</h5>
        <small class="text-muted">Updated 1 month ago</small>
      </div>
      <div class="card-body d-flex align-items-end">
        <div class="w-100">
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-primary me-4 p-2"><i class="ti ti-chart-pie-2 ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{$numberOfSales}}</h5>
                  <small>Sales</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-users ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{$numberOfClients}}</h5>
                  <small>Customers</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-danger me-4 p-2"><i class="ti ti-shopping-cart ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{$numberOfProducts}}</h5>
                  <small>Products</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded bg-label-success me-4 p-2"><i class="ti ti-currency-dollar ti-lg"></i></div>
                <div class="card-info">
                  <h5 class="mb-0">{{$revenue}} MAD</h5>
                  <small>Revenue</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Statistics -->

  <!-- Popular Product -->
  <div class="col-xxl-4 col-md-6">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
                <h5 class="mb-1">Popular Products</h5>
                <p class="card-subtitle">Total {{ $popularProducts->count() }} Products</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="popularProduct" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-dots-vertical ti-md text-muted"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="popularProduct">
                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="p-0 m-0">
                @foreach ($popularProducts as $product)
                    <li class="d-flex mb-6">
                        <div class="me-4">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" width="46">
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">{{ $product->name }}</h6>
                                <small class="text-body d-block">Item: #{{ $product->id }}</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                                <p class="mb-0">{{ number_format($product->price, 2) }} mad</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

  <!--/ Popular Product -->

  

  <!-- Invoice table -->
  <div class="col-xxl-8">
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
  </div>
  <!-- /Invoice table -->
</div>

<script>
  function openProductModal(product) {
       document.getElementById('view-product-name').textContent = product.name;
       document.getElementById('view-product-description').textContent = product.description;
       document.getElementById('view-product-price').textContent = product.price + ' MAD';
       document.getElementById('view-product-category').textContent = product.category;
       document.getElementById('view-product-date').textContent = new Date(product.created_at).toLocaleDateString();
       document.getElementById('view-product-image').src = product.image;
       let modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
             modal.show();
       }
 </script>
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
