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
<div class="card mb-6">
  <div class="card-widget-separator-wrapper">
    <div class="card-body card-widget-separator">
      <div class="row gy-4 gy-sm-1">
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
            <div>
              <p class="mb-1">In-store Sales</p>
              <h4 class="mb-1">$5,345.43</h4>
              <p class="mb-0"><span class="me-2">5k orders</span><span class="badge bg-label-success">+5.7%</span></p>
            </div>
            <span class="avatar me-sm-6">
              <span class="avatar-initial rounded"><i class="ti-28px ti ti-smart-home text-heading"></i></span>
            </span>
          </div>
          <hr class="d-none d-sm-block d-lg-none me-6">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
            <div>
              <p class="mb-1">Website Sales</p>
              <h4 class="mb-1">$674,347.12</h4>
              <p class="mb-0"><span class="me-2">21k orders</span><span class="badge bg-label-success">+12.4%</span></p>
            </div>
            <span class="avatar p-2 me-lg-6">
              <span class="avatar-initial rounded"><i class="ti-28px ti ti-device-laptop text-heading"></i></span>
            </span>
          </div>
          <hr class="d-none d-sm-block d-lg-none">
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
            <div>
              <p class="mb-1">Discount</p>
              <h4 class="mb-1">$14,235.12</h4>
              <p class="mb-0">6k orders</p>
            </div>
            <span class="avatar p-2 me-sm-6">
              <span class="avatar-initial rounded"><i class="ti-28px ti ti-gift text-heading"></i></span>
            </span>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="mb-1">Affiliate</p>
              <h4 class="mb-1">$8,345.23</h4>
              <p class="mb-0"><span class="me-2">150 orders</span><span class="badge bg-label-danger">-3.5%</span></p>
            </div>
            <span class="avatar p-2">
              <span class="avatar-initial rounded"><i class="ti-28px ti ti-wallet text-heading"></i></span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Product List Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">Products</h5>
    {{-- <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0">
      <div class="col-md-4 product_status"></div>
      <div class="col-md-4 product_category"></div>
      <div class="col-md-4 product_stock"></div>
    </div> --}}
    <a href="/product-add">
      <button type="button" class="btn btn-primary" > 
      <i class="ti ti-plus me-1"></i> Add Product
      </button>
    </a>
    
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-products table">
        <thead class="border-top">
            <tr>
                <th></th>
                <th></th>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center product-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar me-4 rounded-2 bg-label-secondary">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product-{{ $product->id }}" class="rounded-2">
                                    @else
                                        <span class="avatar-initial rounded-2 bg-label-primary">{{ substr($product->name, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="text-nowrap mb-0">{{ $product->name }}</h6>
                                <small class="text-truncate d-none d-sm-block">{{ $product->description }}</small> <!-- Replace with actual brand if available -->
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-truncate d-flex align-items-center text-heading">
                            {{ $product->category ? $product->category->name : 'Uncategorized' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-truncate">
                            @if ($product->stock > 0)
                                <label class="switch switch-primary switch-sm">
                                    <input type="checkbox" class="switch-input" checked="">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                    </span>
                                </label>
                            @else
                                <label class="switch switch-primary switch-sm">
                                    <input type="checkbox" class="switch-input">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-off"></span>
                                    </span>
                                </label>
                            @endif
                        </span>
                    </td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if ($product->status === 'Publié')
                            <span class="badge bg-label-success">Publish</span>
                        @elseif ($product->status === 'Planifié')
                            <span class="badge bg-label-warning">Scheduled</span>
                        @else
                            <span class="badge bg-label-danger">Inactive</span>
                        @endif
                    </td>
                    <script>
                      document.addEventListener('DOMContentLoaded', function () {
                      // Add event listeners to all edit buttons
                      document.querySelectorAll('.edit-product-btn').forEach(button => {
                        button.addEventListener('click', function () {
                          // Get the product data from data attributes
                          const productId = button.getAttribute('data-product-id');
                          const productName = button.getAttribute('data-product-name');
                          const productDescription = button.getAttribute('data-product-description');
                          const productSku = button.getAttribute('data-product-sku');
                          const productBarcode = button.getAttribute('data-product-barcode');
                          const productPrice = button.getAttribute('data-product-price');
                          const productCategoryId = button.getAttribute('data-product-category-id');
                          const productStatus = button.getAttribute('data-product-status');
                          const productImage = button.getAttribute('data-product-image');

                          // Update the form action URL
                          const form = document.getElementById('editProductForm');
                          form.action = `/products/${productId}`;

                          // Populate the form fields
                          document.getElementById('edit-product-name').value = productName;
                          document.getElementById('edit-product-description').value = productDescription;
                          document.getElementById('edit-product-sku').value = productSku;
                          document.getElementById('edit-product-barcode').value = productBarcode;
                          document.getElementById('edit-product-price').value = productPrice;
                          document.getElementById('edit-product-category').value = productCategoryId;
                          document.getElementById('edit-product-status').value = productStatus;

                          // Update the image preview
                          const imagePreview = document.getElementById('edit-product-image-preview');
                          if (productImage) {
                            imagePreview.src = `/storage/${productImage}`;
                            imagePreview.style.display = 'block';
                          } else {
                            imagePreview.style.display = 'none';
                          }
                        });
                      });
                    });
                    </script>
                    <td>
                        <div class="d-inline-block text-nowrap">
                          <button  
                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-product-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#editProductModal"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-description="{{ $product->description }}"
                            data-product-sku="{{ $product->sku }}"
                            data-product-barcode="{{ $product->barcode }}"
                            data-product-price="{{ $product->price }}"
                            data-product-category-id="{{ $product->category_id }}"
                            data-product-status="{{ $product->status }}"
                            data-product-image="{{ $product->image }}"
                          >
                            <i class="ti ti-edit ti-md"></i>
                          </button>
                          <script>
                            function deleteItem(itemId) {
  // Confirm before deleting (optional)
  if (!confirm("Are you sure you want to delete this item?")) {
    return;
  }

  // Send an AJAX request to your backend
  fetch(`/delete_product/${itemId}`, {
    method: 'POST', // or 'POST' depending on your backend
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // Add CSRF token if needed
    }
  })
  .then(response => {
    if (response.ok) {
      // Handle success (e.g., show a message or reload the page)
      alert("Item deleted successfully!");
      window.location.reload(); // Reload the page or update the UI dynamically
    } else {
      // Handle error
      alert("Failed to delete the item.");
    }
  })
  .catch(error => {
    console.error("Error:", error);
  });
}
                          </script>
                          
                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light " data-bs-toggle="modal" data-bs-target="#modalCenter">
                              <i class="ti ti-trash ti-md"></i> 
                            </button>
                          
                            
                            
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>


<!-- Single Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT') <!-- Use PUT method for updates -->

          <!-- Name -->
          <div class="mb-3">
            <label for="edit-product-name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="edit-product-name" name="productTitle" required>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label for="edit-product-description" class="form-label">Description</label>
            <textarea class="form-control" id="edit-product-description" name="description"></textarea>
          </div>

          <!-- SKU -->
          <div class="mb-3">
            <label for="edit-product-sku" class="form-label">SKU</label>
            <input type="text" class="form-control" id="edit-product-sku" name="productSku" required>
          </div>

          <!-- Barcode -->
          <div class="mb-3">
            <label for="edit-product-barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="edit-product-barcode" name="productBarcode" required>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label for="edit-product-price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="edit-product-price" name="productPrice" required>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label for="edit-product-category" class="form-label">Category</label>
            <select class="form-select" id="edit-product-category" name="category">
              <option value="">Select Category</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label for="edit-product-status" class="form-label">Status</label>
            <select class="form-select" id="edit-product-status" name="status">
              <option value="Publié">Publié</option>
              <option value="Planifié">Planifié</option>
              <option value="Inactif">Inactif</option>
            </select>
          </div>

          <!-- Image -->
          <div class="mb-3">
            <label for="edit-product-image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="edit-product-image" name="file">
            <img id="edit-product-image-preview" src="" alt="Product Image" class="img-thumbnail mt-2" width="100" style="display: none;">
          </div>

          <!-- Submit Button -->
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <div class="modal-body">
        
        <h5 class="modal-title" id="modalCenterTitle">Are you sure you want to delete the product ?</h5>
        
      </div> 
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="/delete_product?id={{ $product->id }}">
          <button type="button" class="btn btn-primary">Yes</button>
        </a>
      </div>
    </div>
  </div>
</div>


@endsection
