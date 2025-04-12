@extends('layouts/layoutMaster')

@section('title', 'eCommerce Order List - Apps')

{{-- Vendor Styles --}}
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
])
@endsection

{{-- Vendor Scripts --}}
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
])
@endsection

{{-- Page Scripts --}}
@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-order-list.js'
])
@endsection

@section('content')
<!-- Order List Widget -->
<div class="card mb-6">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0">56</h4>
              <p class="mb-0">Paiment en cours</p>
            </div>
            <span class="avatar me-sm-6">
              <span class="avatar-initial bg-label-secondary rounded text-heading">
                <i class="ti-26px ti ti-calendar-stats text-heading"></i>
              </span>
            </span>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-6">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
            <div>
              <h4 class="mb-0">126</h4>
              <p class="mb-0">Completé</p>
            </div>
            <span class="avatar p-2 me-lg-6">
              <span class="avatar-initial bg-label-secondary rounded">
                <i class="ti-26px ti ti-checks text-heading"></i>
              </span>
            </span>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
            <div>
              <h4 class="mb-0">12</h4>
              <p class="mb-0">Rembourssé</p>
            </div>
            <span class="avatar p-2 me-sm-6">
              <span class="avatar-initial bg-label-secondary rounded">
                <i class="ti-26px ti ti-wallet text-heading"></i>
              </span>
            </span>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h4 class="mb-0">4</h4>
              <p class="mb-0">Echoué</p>
            </div>
            <span class="avatar p-2">
              <span class="avatar-initial bg-label-secondary rounded">
                <i class="ti-26px ti ti-alert-octagon text-heading"></i>
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Order List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-order table border-top">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>Commande</th>
          <th>date</th>
          <th>Clients</th>
          <th>Paiment</th>
          <th>Status</th>
          <th>Méthode</th>
          <th>actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Offcanvas to add new order -->
  <div
  class="offcanvas offcanvas-end"
  tabindex="-1"
  id="offcanvasEcommerceOrderAdd"
  aria-labelledby="offcanvasEcommerceOrderAddLabel"
  >
  @can('create commandes')
    <div class="offcanvas-header">
    <h5 id="offcanvasEcommerceOrderAddLabel" class="offcanvas-title">Ajouter Commande</h5>
    <button
      type="button"
      class="btn-close text-reset"
      data-bs-dismiss="offcanvas"
      aria-label="Close"
    ></button>
  </div>
  @endcan
  

  <div class="offcanvas-body border-top mx-0 flex-grow-0">
    {{-- Add Order Form --}}
    <form class="ecommerce-order-add pt-0" id="eCommerceOrderAddForm" onsubmit="return false">
      <!-- CHOOSE CLIENT -->
      <div class="mb-4">
        <h6 class="mb-4">Order Information</h6>
        <label class="form-label" for="ecommerce-order-add-client">Client*</label>
        <select
          id="ecommerce-order-add-client"
          class="form-select"
          name="client_id"
          required
        >
          <option value="">-- Choisir Client --</option>
          @foreach($clients as $client)
            <option value="{{ $client->id }}">
              {{ $client->name }} 
            </option>
          @endforeach
        </select>
      </div>

      <!-- PAIEMENT RADIO GROUP -->
      <div class="mb-4">
        <label class="form-label d-block">Paiement</label>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paiement"
            id="paiementEnCours"
            value="en cours"
            checked
          />
          <label class="form-check-label" for="paiementEnCours">En cours</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paiement"
            id="paiementPaye"
            value="payé"
          />
          <label class="form-check-label" for="paiementPaye">Payé</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paiement"
            id="paiementEchoue"
            value="échoué"
          />
          <label class="form-check-label" for="paiementEchoue">Echoué</label>
        </div>
      </div>

      <!-- STATUS RADIO GROUP -->
      <div class="mb-4">
        <label class="form-label d-block">Status</label>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="status"
            id="statusInitiee"
            value="initiée"
            checked
          />
          <label class="form-check-label" for="statusInitiee">Initiée</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="status"
            id="statusEncours"
            value="en cours"
          />
          <label class="form-check-label" for="statusEncours">En cours</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="status"
            id="statusTerminee"
            value="terminée"
          />
          <label class="form-check-label" for="statusTerminee">Terminée</label>
        </div>
      </div>

      <!-- METHODE RADIO GROUP -->
      <div class="mb-4">
        <label class="form-label d-block">Méthode</label>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="methode"
            id="methodMastercard"
            value="mastercard-cc"
            checked
          />
          <label class="form-check-label" for="methodMastercard">Mastercard</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="methode"
            id="methodVisa"
            value="visa-cc"
          />
          <label class="form-check-label" for="methodVisa">Visa</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="methode"
            id="methodPaypal"
            value="paypal"
          />
          <label class="form-check-label" for="methodPaypal">PayPal</label>
        </div>
      </div>

      <!-- BUTTONS -->
      <div>
        <button
          type="submit"
          class="btn btn-primary me-sm-4 data-submit"
          id="orderSubmitBtn"
        >
          Ajouter
        </button>
        <button
          type="reset"
          class="btn btn-label-danger"
          data-bs-dismiss="offcanvas"
        >
          Discard
        </button>
      </div>
    </form>
  </div>
  </div>

</div>
@endsection

<script>
  // If needed, expose these URLs to your JavaScript:
  // e.g. route to get your JSON data, store, etc.
  window.ORDERS_DATA_URL = "{{ route('commande.data') }}";
  window.ORDERS_STORE_URL = "{{ route('commande.store') }}";
</script>

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
