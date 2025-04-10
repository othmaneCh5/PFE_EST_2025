@extends('layouts/layoutMaster')

@section('title', 'eCommerce Referrals - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-referral.js'
])
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <h5>Orders Table</h5>
      <button 
      type="button" 
      class="btn btn-primary" 
      data-bs-toggle="modal" 
      data-bs-target="#largeModal"> 
      <i class="ti ti-plus me-1"></i> Add Order
      </button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Fournisseur</th>
          <th>Product</th>
          <th>Status</th>
          <th>quantity</th>
          <th>price</th>
          <th>date</th>
          <th>actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td>#{{ $order->id}}</td>
            <td>
              <div class="d-flex justify-content-start align-items-center fournisseur-name">
                  <div class="avatar-wrapper">
                      <div class="avatar avatar-sm me-4">
                          @if ($order->fournisseur->profile_photo_path)
                              <img src="{{ asset('storage/' . $order->fournisseur->profile_photo_path) }}" alt="Avatar" class="rounded-circle">
                          @else
                              @php
                                  $states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                                  $state = $states[array_rand($states)];
                                  $initials = implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode(' ', $order->fournisseur->name)));
                              @endphp
                              <span class="avatar-initial rounded-circle bg-label-{{ $state }}">{{ $initials }}</span>
                          @endif
                      </div>
                  </div>
                  <div class="d-flex flex-column">
                      {{-- <a href="{{ route('app-user-view', $user->id) }}" class="text-heading text-truncate"> --}}
                          <span class="fw-medium">{{ $order->fournisseur->name }}</span>
                      {{-- </a> --}}
                      <small>{{ $order->fournisseur->email }}</small>
                  </div>
              </div>
          </td>
           
          <td>
            <div class="d-flex justify-content-start align-items-center product-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar me-4 rounded-2 bg-label-secondary">
                        @if ($order->product->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}" alt="Product-{{ $order->product->id }}" class="rounded-2">
                        @else
                            <span class="avatar-initial rounded-2 bg-label-primary">{{ substr($order->product->name, 0, 2) }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h6 class="text-nowrap mb-0">{{ $order->product->name }}</h6>
                     <!-- Replace with actual brand if available -->
                </div>
            </div>
        </td>

            <td>
              @php
                $statusClasses = [
                  'pending' => 'bg-label-warning',
                  'received' => 'bg-label-success',
                  'canceled' => 'bg-label-danger',
                ];
              @endphp

              <span class="badge {{ $statusClasses[$order->status] ?? 'bg-label-secondary' }}">
                {{ ucfirst($order->status ?? 'Unknown') }}
              </span>

            </td>
            <td>{{ $order->quantity }}</td>
            <td class="text-heading">{{ $order->price }}</td>
            <td>{{$order->order_date}}</td>
            <td class="text-lg-center">
              <div class="d-flex align-items-sm-center justify-content-sm-center">
                <button type="button" class="btn btn-sm btn-icon btn-text-success rounded-pill waves-effect waves-light confirm-order-btn" data-id="{{ $order->id }}">
                  <i class="ti ti-check ti-md"></i>
                </button>
            
                <button type="button" class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect waves-light cancel-order-btn" data-id="{{ $order->id }}">
                  <i class="ti ti-x ti-md"></i>
                </button>
              </div>
            </td>
            
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                // Confirm Order
                document.querySelectorAll('.confirm-order-btn').forEach(button => {
                  button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
            
                    fetch(`/orders/${orderId}/confirm`, {
                      method: 'POST',
                      headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                      },
                    })
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        location.reload(); // or update DOM dynamically
                      }
                    });
                  });
                });
            
                // Cancel Order
                document.querySelectorAll('.cancel-order-btn').forEach(button => {
                  button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
            
                    fetch(`/orders/${orderId}/cancel`, {
                      method: 'POST',
                      headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                      },
                    })
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        location.reload(); // or update DOM dynamically
                      }
                    });
                  });
                });
              });
            </script>
            

          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No referrals found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


<div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
  <div style=" left:300px; "  class="modal-dialog modal-lg modal-dialog-centered" role="document"> 
    <form  action="/add_order" method="post" >
      @csrf
      <div  style=" margin:10px "  class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="text-align: center; font-size: 1.5em; margin: 0 auto; display: block;" id="exampleModalLabel3">place a new order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <script>
          document.addEventListener('DOMContentLoaded', function () {
            const fournisseurSelect = document.getElementById('fournisseurSelect');
            const productSelect = document.getElementById('productSelect');
        
            fournisseurSelect.addEventListener('change', function () {
              const fournisseurId = this.value;
        
              // Clear existing options
              productSelect.innerHTML = '<option value="">Loading...</option>';
        
              if (fournisseurId) {
                fetch(`/fournisseur/${fournisseurId}/products`)
                  .then(response => response.json())
                  .then(data => {
                    productSelect.innerHTML = '<option value="">Select a product</option>';
                    data.forEach(product => {
                      const option = document.createElement('option');
                      option.value = product.id;
                      option.textContent = product.name;
                      productSelect.appendChild(option);
                    });
                  })
                  .catch(error => {
                    productSelect.innerHTML = '<option value="">Error loading products</option>';
                    console.error('Error fetching products:', error);
                  });
              } else {
                productSelect.innerHTML = '<option value="">Select a product</option>';
              }
            });
          });
        </script>
        
        <!-- Fournisseur Select -->
<div class="row g-6" style="position: relative; top:20px;">
  <div class="mb-6 col ecommerce-select2-dropdown">
    <label class="form-label mb-1" for="fournisseurSelect">
      <span>Fournisseur</span>
    </label>
    <select name="fournisseur_id" id="fournisseurSelect" class="select2 form-select">
      <option value="">Select Fournisseur</option>
      @foreach($fournisseurs as $fournisseur)
        <option value="{{ $fournisseur->id }}">{{ $fournisseur->name }}</option>
      @endforeach 
    </select>
  </div>
</div>

<!-- Product Select -->
<div class="row g-6" style="position: relative; top:20px;">
  <div class="mb-6 col ecommerce-select2-dropdown">
    <label class="form-label mb-1" for="productSelect">
      <span>Product</span>
    </label>
    <select name="product_id" id="productSelect" class="select2 form-select">
      <option value="">Select a product</option>
      {{-- Products will be loaded dynamically --}}
    </select>
  </div>
</div>


      <div style="position: relative ; top:10px;" class="mb-3">
        <label for="edit-product-name" class="form-label">Quantity</label>
        <input type="number" class="form-control" id="edit-product-name" name="quantity" required>
      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    </form>
    
  </div>
</div>
@endsection