@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Add - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/quill/typography.scss',
  'resources/assets/vendor/libs/quill/katex.scss',
  'resources/assets/vendor/libs/quill/editor.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/dropzone/dropzone.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/tagify/tagify.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/quill/katex.js',
  'resources/assets/vendor/libs/quill/quill.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/dropzone/dropzone.js',
  'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/tagify/tagify.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-ecommerce-product-add.js'
])
@endsection

@section('content')
<form action="/add_product" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="app-ecommerce">
      <!-- Add Product -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
          <div class="d-flex flex-column justify-content-center">
              <h4 class="mb-1">Add a new Product</h4>
              <p class="mb-0">Orders placed across your store</p>
          </div>
          <button type="submit" class="btn btn-primary">Publish product</button>
      </div>

      <div class="row">
          <!-- First column-->
          <div class="col-12 col-lg-8">
              <!-- Product Information -->
              <div class="card mb-6" >
                  <div class="card-header">
                      <h5 class="card-tile mb-0">Product information</h5>
                  </div>
                  <div class="card-body">
                      <div class="mb-6">
                          <label class="form-label" for="ecommerce-product-name">Name</label>
                          <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Product title" name="productTitle" aria-label="Product title" required>
                      </div>
                      <div class="row mb-6">
                          <div class="col">
                              <label class="form-label" for="ecommerce-product-sku">SKU</label>
                              <input type="text" class="form-control" id="ecommerce-product-sku" placeholder="SKU" name="productSku" aria-label="Product SKU" required>
                          </div>
                          <div class="col">
                              <label class="form-label" for="ecommerce-product-barcode">Barcode</label>
                              <input type="text" class="form-control" id="ecommerce-product-barcode" placeholder="0123-4567" name="productBarcode" aria-label="Product barcode" required>
                          </div>
                      </div>
                      <!-- Description -->
                      <div>
                          <label class="mb-1">Description (Optional)</label>
                          <textarea class="form-control" name="description" id="ecommerce-category-description" rows="4" style="min-height: 180px;"></textarea>
                      </div>
                  </div>
              </div>
              <!-- /Product Information -->
              <!-- Media -->
              <script>
                document.getElementById('fileInput').addEventListener('change', function (e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier choisi';
    document.getElementById('fileName').textContent = fileName;
});
              </script>
              <div class="card mb-6" style="width: 153%;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">Product Image</h5>
                    <a href="javascript:void(0);" class="fw-medium">Add media from URL</a>
                </div>
                <div class="card-body">
                    <div class="dz-message needsclick">
                        <p class="h4 needsclick pt-3 mb-2">Drag and drop your image here</p>
                        <p class="h6 text-muted d-block fw-normal mb-2">or</p>
                        <label for="fileInput" class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse">Browse image</label>
                    </div>
                    <div class="fallback">
                        <input name="file" type="file" id="fileInput" style="display: none;" />
                        <p id="fileName" class="mt-2 text-muted"></p> <!-- Display the selected file name -->
                    </div>
                </div>
            </div>
              <!-- /Media -->
          </div>
          <!-- /Second column -->

          <!-- Second column -->
          <div class="col-12 col-lg-4">
              <!-- Pricing Card -->
              <div class="card mb-6">
                  <div class="card-header">
                      <h5 class="card-title mb-0">Pricing</h5>
                  </div>
                  <div class="card-body">
                      <!-- Base Price -->
                      <div class="mb-6">
                          <label class="form-label" for="ecommerce-product-price">Base Price</label>
                          <input type="number" class="form-control" id="ecommerce-product-price" placeholder="Price" name="productPrice" aria-label="Product price" required>
                      </div>
                  </div>
              </div>
              <div class="card mb-6">
                  <div class="card-header">
                      <h5 class="card-title mb-0">Category</h5>
                  </div>
                  <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                              <label class="form-label mb-1" for="category-org">
                                  <span>Category</span>
                              </label>
                              <select name="category" id="category-org" class="select2 form-select" data-placeholder="Select Category">
                                  <option value="">Select Category</option>
                                  {{-- @foreach($categories as $category)
                                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach --}}
                              </select>
                          </div>
                          <a href="javascript:void(0);" class="fw-medium btn btn-icon btn-label-primary ms-4"><i class='ti ti-plus ti-md'></i></a>
                      </div>
                      <div class="mb-6 col ecommerce-select2-dropdown">
                          <label class="form-label mb-1" for="status-org">Status</label>
                          <select id="status-org" class="form-select" name="status" required>
                              <option value="Publié">Publié</option>
                              <option value="Planifié">Planifié</option>
                              <option value="Inactif">Inactif</option>
                          </select>
                      </div>
                  </div>
              </div>
              <!-- /Organize Card -->
          </div>
          <!-- /Second column -->
      </div>
  </div>
</form>


@endsection
