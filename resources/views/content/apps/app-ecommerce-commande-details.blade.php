@extends('layouts/layoutMaster')

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

@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-order-details.js',
  'resources/assets/js/modal-add-new-address.js',
  'resources/assets/js/modal-edit-user.js'
])
@endsection

@section('content')
@php
    // For convenience
    $client = $commande->client;
    $method = $commande->methode;

    // Mask card number if any
    $maskedCard = null;
    if ($client && $client->num_carte) {
        // For example: '************1234'
        $last4 = substr($client->num_carte, -4);
        $maskedCard = '************' . $last4;
    }
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

  <div class="d-flex flex-column justify-content-center">
    <div class="mb-1">
      {{-- Show the order ID dynamically --}}
      <span class="h5">Order #{{ $commande->id }}</span>
      {{-- We could show paiement or status badges if you want --}}
      @if(strtolower($commande->paiement) === 'payé')
        <span class="badge bg-label-success ms-2">payé</span>
      @elseif(strtolower($commande->paiement) === 'échoué')
        <span class="badge bg-label-danger ms-2">Paiment échoué</span>
      @else
        <span class="badge bg-label-warning ms-2">Paiment {{ ucfirst($commande->paiement) }}</span>
      @endif

      @if(strtolower($commande->status) === 'en cours')
        <span class="badge bg-label-info ms-1">En cours</span>
      @elseif(strtolower($commande->status) === 'terminée')
        <span class="badge bg-label-success ms-1">terminée</span>
      @else
        <span class="badge bg-label-warning ms-1">{{ ucfirst($commande->status) }}</span>
      @endif
    </div>
    {{-- Display the created_at date/time in your desired format --}}
    <p class="mb-0">
      {{ $commande->created_at->format('M d, Y, H:i') }}
    </p>
  </div>

  <div class="d-flex align-content-center flex-wrap gap-2">
    <button class="btn btn-label-danger delete-order" data-id="{{ $commande->id }}">
      Delete Order
    </button>
  </div>
</div>

<!-- Order Details Table -->
<div class="row">
  <div class="col-12 col-lg-8">
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0">Order details</h5>
        <h6 class="m-0">
          <a href="{{ route('commande.addProduct', $commande->id) }}">
            Ajouter produits
          </a>
        </h6>
      </div>
      <div class="card-datatable table-responsive">
        <table class="table border-top">
          <thead>
            <tr>
              <th></th>
              <th>Produit</th>
              <th>Prix</th>
              <th>Quantité</th>
              <th>Total</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($commande->products as $product)
              <tr>
                <td style="width:40px;">
                  <input type="checkbox" class="form-check-input" />
                </td>
                <td>
                  {{-- <img src="{{ asset('assets/img/products/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-circle me-3" width="50" height="50"> --}}
                  <img src="{{ asset('assets/img/products/woodenchair.png') }}" alt="" class="rounded-circle me-3" width="50" height="50">

                 {{ $product->name }}
                </td>
                <td>${{ $product->price }}</td>
                <td>{{ $product->pivot->qte }}</td>
                <td>{{ $product->pivot->qte * $product->price }}</td>
                <td>
                  <!-- Trash bin icon to remove product from pivot -->
                  <form
                    action="{{ route('commande.detachProduct', ['commande' => $commande->id, 'product' => $product->id]) }}"
                    method="POST"
                    onsubmit="return confirm('Confirmer la suppression ?')"
                  >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-label-danger">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        @php
          $subtotal = $commande->products->sum(function ($product) {
              return $product->price * $product->pivot->qte;
          });

          $tax = $subtotal * 0.01; //  1% tax
          $total = $subtotal + $tax;
        @endphp

        <div class="d-flex justify-content-end align-items-start m-6 mb-2">
          <div class="order-calculations">
            <div class="d-flex justify-content-start mb-2">
              <span class="w-px-100 text-heading">Subtotal:</span>
              <h6 class="mb-0">${{ number_format($subtotal, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-start mb-2">
              <span class="w-px-100 text-heading">Tax:</span>
              <h6 class="mb-0">${{ number_format($tax, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-start mb-4">
              <h6 class="w-px-100 mb-0">Total:</h6>
              <h6 class="mb-0">${{ number_format($total, 2) }}</h6>
            </div>

            <!-- Button right under total -->
            <a href="{{ url('/front-pages/payment') }}" class="btn btn-success">
              <span class="me-2">Proceed with Payment</span>
              <i class="ti ti-arrow-right scaleX-n1-rtl"></i>
            </a>
          </div>
        </div>


      </div>
    </div>
    <div class="card mb-6">
      <div class="card-header">
        <h5 class="card-title m-0">Shipping activity</h5>
      </div>
      <div class="card-body pt-1">
        <ul class="timeline pb-0 mb-0">
          <!-- Always show first step -->
          <li class="timeline-item timeline-item-transparent border-primary">
            <span class="timeline-point timeline-point-primary"></span>
            <div class="timeline-event">
              <div class="timeline-header">
                <h6 class="mb-0">Commande placée (#{{ $commande->id }})</h6>
                <small class="text-muted">{{ $commande->created_at->format('M d, Y, H:i') }}</small>
              </div>
              <p class="mt-3">Commande placée avec succès</p>
            </div>
          </li>
    
          @if($commande->status === 'en cours')
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Expédition en cours</h6>
                  <small class="text-muted"></small>
                </div>
                <p class="mt-3">Ramassage prévu avec le transporteur</p>
              </div>
            </li>
          @endif
    
          @if($commande->status === 'terminée')
            <li class="timeline-item timeline-item-transparent border-primary">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Expédition en cours</h6>
                  <small class="text-muted"></small>
                </div>
                <p class="mt-3">Ramassage prévu avec le transporteur</p>
              </div>
          </li>
            <li class="timeline-item timeline-item-transparent border-success">
              <span class="timeline-point timeline-point-success"></span>
              <div class="timeline-event">
                <div class="timeline-header">
                  <h6 class="mb-0">Commande livrée</h6>
                  <small class="text-muted"></small>
                </div>
                <p class="mt-3 mb-3">Le client a reçu la commande avec succès</p>
              </div>
            </li>
          @endif
        </ul>
      </div>
    </div>
    
  </div>

  {{-- RIGHT COLUMN --}}
  <div class="col-12 col-lg-4">
    <div class="card mb-6">
      <div class="card-header">
        <h5 class="card-title m-0">Customer details</h5>
      </div>
      <div class="card-body">
        @if($client)
          <div class="d-flex justify-content-start align-items-center mb-6">
            <div class="avatar me-3">
              {{-- You could show an avatar if you store it --}}
              <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
            </div>
            <div class="d-flex flex-column">
              {{-- Name + ID --}}
              <h6 class="mb-0">{{ $client->name }}</h6>
              <small>ID Client: {{ $client->id }}</small>
            </div>
          </div>
          {{-- Show how many orders if you have logic for that, e.g. $client->commandes->count() --}}
          <div class="d-flex justify-content-start align-items-center mb-6">
            <span class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center">
              <i class='ti ti-shopping-cart ti-lg'></i>
            </span>
            <h6 class="text-nowrap mb-0">{{ $client->commandes->count() }} Orders</h6>
          </div>
          <div class="d-flex justify-content-between">
            <h6 class="mb-1">Contact info</h6>
          </div>
          <p class=" mb-1">Email: {{ $client->email }}</p>
          <p class=" mb-0">Tel: (+212) {{ $client->tel }}</p>
        @else
          <p class="text-muted">No client info found.</p>
        @endif
      </div>
    </div>

    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">Shipping address</h5>
        <h6 class="m-0">
          {{-- <a href="javascript:void(0)">Edit</a> --}}
        </h6>
      </div>
      <div class="card-body">
        @if($client)
        @foreach(explode(',', $client->adresse) as $line)
          <p class="mb-0">{{ trim($line) }}</p>
        @endforeach
        @else
          <p class="mb-0 text-muted">No address available</p>
        @endif
      </div>
    </div>

    {{-- Payment method card (we skip separate “billing address” as requested) --}}
    <div class="card mb-6">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0">Payment method</h5>
        <h6 class="m-0">
          @if($method == 'mastercard-cc' || $method == 'visa')
          <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewCCModal">Edit</a>
          @endif
        </h6>
      </div>
      <div class="card-body">
        @if($method === 'paypal')
          <h5 class="mb-1">PayPal</h5>
          <p class="mb-0">Paid via PayPal</p>
        @else
          {{-- e.g. for 'mastercard-cc', 'visa-cc', etc. --}}
          <h5 class="mb-1">
            @if(str_contains($method, 'mastercard'))
              Mastercard
            @elseif(str_contains($method, 'visa'))
              Visa
            @else
              Credit Card
            @endif
          </h5>
          @if($maskedCard)
            <p class="mb-0">Card Number: {{ $maskedCard }}</p>
            <p class="mb-0">Exp. date: {{ $client->exp_date }}</p>
          @else
            <p class="mb-0 text-muted">No card details found</p>
          @endif
        @endif
      </div>
    </div>


    <!-- Add / Edit Credit Card Modal -->
    <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 class="mb-2">Update Card</h4>
              <p>Edit your saved payment details</p>
            </div>
            <form method="POST" action="{{ route('client.updateCard', $client->id) }}" class="row g-6">
              @csrf
              @method('PUT')
              <div class="col-12">
                <label class="form-label w-100" for="modalAddCard">Card Number</label>
                <div class="input-group input-group-merge">
                  <input
                    id="modalAddCard"
                    name="num_carte"
                    class="form-control credit-card-mask"
                    type="text"
                    value="{{ $client->num_carte }}"
                    placeholder="1356 3215 6548 7898"
                  />
                  <span class="input-group-text p-1"><span class="card-type"></span></span>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label" for="modalAddCardName">Name</label>
                <input type="text" name="name" id="modalAddCardName" class="form-control" value="{{ $client->name }}" />
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label" for="modalAddCardExpiryDate">Exp. Date</label>
                <input
                  type="text"
                  name="exp_date"
                  id="modalAddCardExpiryDate"
                  class="form-control expiry-date-mask"
                  value="{{ \Carbon\Carbon::parse($client->exp_date)->format('m/y') }}"
                  placeholder="MM/YY"
                />
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label" for="modalAddCardCvv">CVV Code</label>
                <div class="input-group input-group-merge">
                  <input
                    type="text"
                    name="cvv_code"
                    id="modalAddCardCvv"
                    class="form-control cvv-code-mask"
                    maxlength="3"
                    value="{{ $client->cvv_code }}"
                    placeholder="654"
                  />
                  <span class="input-group-text cursor-pointer ps-0" id="modalAddCardCvv2">
                    <i class="text-muted ti ti-help" data-bs-toggle="tooltip" title="Card Verification Value"></i>
                  </span>
                </div>
              </div>

              <div class="col-12">
                <div class="form-check form-switch">
                  <input type="checkbox" class="form-check-input" id="futureAddress" checked />
                  <label for="futureAddress" class="switch-label">Save card for future billing</label>
                </div>
              </div>

              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary me-3">Update</button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- / Add New Credit Card Modal -->
  </div>
</div>

<!-- Modals (if any) -->
@include('_partials._modals.modal-edit-user')
@include('_partials._modals.modal-add-new-address')
@include('_partials._modals.modal-add-new-cc')


@endsection
