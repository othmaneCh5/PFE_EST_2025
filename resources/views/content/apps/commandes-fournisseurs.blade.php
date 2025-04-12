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
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
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
                  'shiped' => 'bg-label-success',
                  'received' => 'bg-label-success',
                  'canceled' => 'bg-label-danger',
                  'rejected' => 'bg-label-danger',
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
                <button type="button" class="btn btn-sm btn-icon btn-text-success rounded-pill waves-effect waves-light ship-order-btn" data-id="{{ $order->id }}">
                  <i class="ti ti-check ti-md"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect waves-light reject-order-btn" data-id="{{ $order->id }}">
                    <i class="ti ti-x ti-md"></i>
                  </button>
              </div>
            </td>
            
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                // Ship Order
                document.querySelectorAll('.ship-order-btn').forEach(button => {
                  button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
            
                    fetch(`/orders/${orderId}/ship`, {
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
            
                // Reject Order
                document.querySelectorAll('.reject-order-btn').forEach(button => {
                  button.addEventListener('click', function () {
                    const orderId = this.dataset.id;
            
                    fetch(`/orders/${orderId}/reject`, {
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



@endsection