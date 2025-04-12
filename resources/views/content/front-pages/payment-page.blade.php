@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Payment - Front Pages')

<!-- Page Styles -->
@section('page-style')
@vite(['resources/assets/vendor/scss/pages/front-page-payment.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite(['resources/assets/vendor/libs/cleavejs/cleave.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/assets/js/pages-pricing.js',
  'resources/assets/js/front-page-payment.js'
])
@endsection

@section('content')
<section class="section-py bg-body first-section-pt">
  <div class="container">
    <div class="card px-3">
      <div class="row">
        <div class="col-lg-7 card-body border-end p-md-8">
          <h4 class="mb-2">Checkout</h4>
          <p class="mb-0">All plans include 40+ advanced tools and features to boost your product. <br>
            Choose the best plan to fit your needs.</p>
            <div class="row g-5 py-8">
              <!-- Visa Option -->
              <div class="col-md-4 col-lg-12 col-xl-4">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center" for="customRadioVisa">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="visa" id="customRadioVisa" {{ ($commande->methode == 'visa') ? 'checked' : '' }} />
                    <span class="custom-option-body">
                      <img src="{{ asset('assets/img/icons/payments/visa-'.$configData['style'].'.png') }}" alt="visa-card" width="58" data-app-light-img="icons/payments/visa-light.png" data-app-dark-img="icons/payments/visa-dark.png">
                      <span class="ms-4 fw-medium text-heading">Visa</span>
                    </span>
                  </label>
                </div>
              </div>
              <!-- Mastercard Option -->
              <div class="col-md-4 col-lg-12 col-xl-4">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center" for="customRadioMastercard">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="mastercard-cc" id="customRadioMastercard" {{ ($commande->methode == 'mastercard-cc') ? 'checked' : '' }} />
                    <span class="custom-option-body">
                      <img src="{{ asset('assets\img\icons\payments\mastercard.png') }}" alt="mastercard" width="58">
                      <span class="ms-4 fw-medium text-heading">Mastercard</span>
                    </span>
                  </label>
                </div>
              </div>
              <!-- PayPal Option -->
              <div class="col-md-4 col-lg-12 col-xl-4">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center" for="customRadioPaypal">
                    <input name="customRadioTemp" class="form-check-input" type="radio" value="paypal" id="customRadioPaypal" {{ ($commande->methode == 'paypal') ? 'checked' : '' }} />
                    <span class="custom-option-body">
                      <img src="{{ asset('assets/img/icons/payments/paypal-'.$configData['style'].'.png') }}" alt="paypal" width="58" data-app-light-img="icons/payments/paypal-light.png" data-app-dark-img="icons/payments/paypal-dark.png">
                      <span class="ms-4 fw-medium text-heading">Paypal</span>
                    </span>
                  </label>
                </div>
              </div>
            </div>
            
            
          <h4 class="mb-6">Billing Details</h4>
          <form>
            <div class="row g-6">
              <div class="col-md-6">
                <label class="form-label" for="billings-email">Email Address</label>
                <input type="text" id="billings-email" class="form-control" placeholder="john.doe@gmail.com"
                       value="{{ $commande->client->email ?? '' }}" />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="billings-name">Name</label>
                <input type="text" id="billings-name" class="form-control" placeholder="John Doe"
                       value="{{ $commande->client->name ?? '' }}" />
              </div>

              <div class="card mb-6">
                <div class="card-header d-flex justify-content-between">
                  <h5 class="card-title m-0">Shipping address</h5>
                </div>
                <div class="card-body">
                  @if(isset($commande->client) && !empty($commande->client->adresse))
                    <input type="text" value="{{ trim($commande->client->adresse) }}" class="form-control" placeholder="Shipping Address" />
                  @else
                    <p class="mb-0 text-muted">No address available</p>
                  @endif
                </div>
              </div>
              
              

            </div>
          </form>
          <div id="form-credit-card">
            <h4 class="mt-8 mb-6">Credit Card Info</h4>
            <form>
              <div class="row g-6">
                <div class="col-12">
                  <label class="form-label" for="billings-card-num">Card number</label>
                  <div class="input-group input-group-merge">
                    <input type="text" id="billings-card-num" class="form-control billing-card-mask" placeholder="Card Number"
                           value="{{ $commande->client->num_carte ?? '' }}" aria-describedby="paymentCard" />
                    <span class="input-group-text cursor-pointer p-1" id="paymentCard"><span class="card-type"></span></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="billings-card-name">Name</label>
                  <input type="text" id="billings-card-name" class="form-control" placeholder="John Doe"
                         value="{{ $commande->client->name ?? '' }}" />
                </div>
                <div class="col-md-3">
                  <label class="form-label" for="billings-card-date">EXP. Date</label>
                  <input type="text" id="billings-card-date" class="form-control billing-expiry-date-mask" placeholder="MM/YY"
                         value="{{ isset($commande->client->exp_date) ? \Carbon\Carbon::parse($commande->client->exp_date)->format('m/y') : '' }}" />
                </div>
                <div class="col-md-3">
                  <label class="form-label" for="billings-card-cvv">CVV</label>
                  <input type="text" id="billings-card-cvv" class="form-control billing-cvv-mask" maxlength="3" placeholder="CVV"
                         value="{{ $commande->client->cvv_code ?? '' }}" />
                </div>
              </div>
            </form>
          </div>
        </div>

        @php
          // Calculate the subtotal from the products attached to the order
          $subtotal = $commande->products->sum(function ($product) {
              return $product->price * $product->pivot->qte;
          });

          // Assume tax is 1% (or adjust your logic)
          $tax = $subtotal * 0.005;
          $total = $subtotal + $tax;
        @endphp

{{-- 
        <div class="col-lg-5 card-body p-md-8">
          <h4 class="mb-2">Order Summary</h4>
          <p class="mb-8">It can help you manage and service orders before,<br> during and after fulfilment.</p>
          <div class="bg-lighter p-6 rounded">
            <p>A simple start for everyone</p>
            <div class="d-flex align-items-center mb-4">
              <h1 class="text-heading mb-0">$59.99</h1>
              <sub class="h6 text-body mb-n3">/month</sub>
            </div>
            <div class="d-grid">
              <button type="button" data-bs-target="#pricingModal" data-bs-toggle="modal" class="btn btn-label-primary">Change Plan</button>
            </div>
          </div> --}}

          

            <div class="mt-5 float-end text-end">
            <div class="d-flex justify-content-end mb-2">
              <span class="w-px-100 text-heading ">Subtotal:</span>
              <h6 class="mb-0 ms-3">${{ number_format($subtotal, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-end mb-2">
              <span class="w-px-100 text-heading text-end">Tax:</span>
              <h6 class="mb-0 ms-3">${{ number_format($tax, 2) }}</h6>
            </div>
            <div class="d-flex justify-content-end mb-4">
              <h6 class="w-px-100 mb-0 text-end">Total:</h6>
              <h6 class="mb-0 ms-3">${{ number_format($total, 2) }}</h6>
            </div>
            <div class="d-grid mt-5">
              <form action="{{ route('orders.confirmPayment', $commande->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-success w-100">
                <span class="me-2">Confirmer le Payment</span>
                <i class="ti ti-arrow-right scaleX-n1-rtl"></i>
              </button>
              </form>
            </div>
            <p class="mt-8 text-center">By continuing, you accept to our Terms of Services and Privacy Policy. Please note that payments are non-refundable.</p>
            </div>
          
          
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal -->
@include('_partials/_modals/modal-pricing')
<!-- /Modal -->
@endsection
