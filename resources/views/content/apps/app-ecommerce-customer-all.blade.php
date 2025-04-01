@extends('layouts/layoutMaster')
@section('title', 'eCommerce Customer All - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
  ])
@endsection

@section('vendor-script')
@vite([
'resources/assets/vendor/libs/moment/moment.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/select2/select2.js',
'resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js',
'resources/assets/vendor/libs/cleavejs/cleave.js',
'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
])
@endsection

@section('page-script')
@vite('resources/assets/js/app-ecommerce-customer-all.js')
@endsection

@section('content')
<!-- customers List Table -->
<div class="card">

  <div class="card-datatable table-responsive">
    <table class="datatables-customers table border-top">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th class="text-nowrap">Id Client</th>
          <th>Client</th>
          <th>Email</th>
          <th>Numéro Téléphone</th>
          <th>Adresse</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new customer -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCustomerAdd" aria-labelledby="offcanvasEcommerceCustomerAddLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasEcommerceCustomerAddLabel" class="offcanvas-title">Add Customer</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body border-top mx-0 flex-grow-0">

      {{-- Ajouter client --}}

      <form class="ecommerce-customer-add pt-0" id="eCommerceCustomerAddForm" onsubmit="return false">
        <div class="ecommerce-customer-add-basic mb-4">
          <h6 class="mb-6">Basic Information</h6>
          <div class="mb-6">
            <label class="form-label" for="ecommerce-customer-add-name">Nom Complet*</label>
            <input type="text" class="form-control" id="ecommerce-customer-add-name" placeholder="John Doe" name="customerName" aria-label="John Doe" />
          </div>
          <div class="mb-6">
            <label class="form-label" for="ecommerce-customer-add-email">Email*</label>
            <input type="text" id="ecommerce-customer-add-email" class="form-control" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="customerEmail" />
          </div>
          <div>
            <label class="form-label" for="ecommerce-customer-add-contact">Num. Téléphone</label>
            <input type="text" id="ecommerce-customer-add-contact" class="form-control phone-mask" placeholder="+(123) 456-7890" aria-label="+(123) 456-7890" name="customerContact" />
          </div>
        </div>

        <div class="ecommerce-customer-add-shiping mb-6 pt-4">
          <h6 class="mb-6">Shipping Information</h6>
          <div class="mb-6">
            <label class="form-label" for="ecommerce-customer-add-address">Addresse</label>
            <input type="text" id="ecommerce-customer-add-address" class="form-control" placeholder="45 Roker Terrace" aria-label="45 Roker Terrace" name="customerAddress1" />
          </div>
          <div class="mb-6">
            <label class="form-label" for="ecommerce-customer-add-town">Ville</label>
            <input type="text" id="ecommerce-customer-add-town" class="form-control" placeholder="New York" aria-label="New York" name="customerTown" />
          </div>
          <div class="col-12 mb-6">
            <label class="form-label" for="ecommerce-customer-add-post-code">Zip Code</label>
            <input type="text" id="ecommerce-customer-add-post-code" class="form-control" placeholder="734990" aria-label="734990" name="pin" pattern="[0-9]{8}" maxlength="8" />
          </div>
          {{-- <div>
            <label class="form-label" for="ecommerce-customer-add-country">Country</label>
            <select id="ecommerce-customer-add-country" class="select2 form-select">
              <option value="">Select</option>
              <option value="Australia">Australia</option>
              <option value="Bangladesh">Bangladesh</option>
              <option value="United States">United States</option>
            </select>
          </div> --}}

        </div>

        <div>
          <button type="submit" class="btn btn-primary me-sm-4 data-submit" id="customerSubmitBtn">Ajouter</button>
          <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

<script>
  console.log("Ajax URL => {{ route('customers.data') }}");
  window.CUSTOMERS_DATA_URL = "{{ route('customers.data') }}";
  window.CUSTOMERS_STORE_URL = "{{ route('customers.store') }}";
</script>

<head> <meta name="csrf-token" content="{{ csrf_token() }}"> </head>
