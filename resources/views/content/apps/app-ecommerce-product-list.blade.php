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
    <h5 class="card-title m-0">Products</h5>

    
        <!-- Search Input -->


        <!-- Filter Dropdown -->
        <div style="position: relative;">
          <select id="category-filter" class="form-select"
                  style="position: absolute; top: 65px; left: 30px; width: 200px; font-size: 14px; z-index: 1050;">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
              <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
            @endforeach
          </select>  
        </div>
        



    @can('create products')
        <a href="/product-add">
            <button type="button" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Add Product
            </button>
        </a>
    @endcan
</div>

  <div class="card-datatable table-responsive">
    <table id="tab" class="datatables-products table">
        <thead class="border-top">
            <tr>
                <th></th>
                <th></th>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Status</th>
                @canany(['edit users', 'delete users'])
                  <th>Actions</th>
                @endcanany
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
                              <h6 class="text-nowrap mb-0">
                                <a href="javascript:void(0);"
                                onclick="openProductModal({
                                  name: '{{ addslashes($product->name) }}',
                                  description: '{{ addslashes($product->description) }}',
                                  price: '{{ $product->price }}',
                                  category: '{{ addslashes($product->category->name ?? 'Uncategorized') }}',
                                  created_at: '{{ $product->created_at }}',
                                  image: '{{ asset('storage/' . $product->image) }}'
                                })"
                                style="color: inherit; text-decoration: none; cursor: pointer;">
                                {{ $product->name }}
                              </a>

                              </h6>
                              
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
                            @if ($product->quantity > 0)
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
                    <td>{{ $product->price }}</td>
                    
                    <td>{{ $product->quantity}}</td>
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
                          const productQuantity = button.getAttribute('data-product-quantity');
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
                          document.getElementById('edit-product-quantity').value = productQuantity;
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
                          @can('edit products')
                          <button  
                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-product-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#editProductModal"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-description="{{ $product->description }}"
                            data-product-quantity="{{ $product->quantity }}"
                            data-product-barcode="{{ $product->barcode }}"
                            data-product-price="{{ $product->price }}"
                            data-product-category-id="{{ $product->category_id }}"
                            data-product-status="{{ $product->status }}"
                            data-product-image="{{ $product->image }}"
                          >
                            <i class="ti ti-edit ti-md"></i>
                          </button>
                          @endcan
                          
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

                          function openProductModal(product) {
                                document.getElementById('view-product-name').textContent = product.name;
                                document.getElementById('view-product-description').textContent = product.description;
                                document.getElementById('view-product-price').textContent = product.price + ' MAD';
                                document.getElementById('view-product-category').textContent = product.category;
                                document.getElementById('view-product-date').textContent = new Date(product.created_at).toLocaleDateString();
                                document.getElementById('view-product-image').src = product.image;

                                // Show modal
                                let modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
                                modal.show();
                              }
                          </script>
                          @can('delete products')
                          <button 
                          type="button" 
                          class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalCenter"
                          data-id="{{ $product->id }}" {{-- Pass the ID here --}}
                          onclick="setDeleteId(this)"
                        >
                          <i class="ti ti-trash ti-md"></i> 
                      </button>
                          @endcan   
                        </div>
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
            <label class="form-label" for="ecommerce-product-qty">Quantity</label>
            <input type="number" class="form-control" id="edit-product-quantity" placeholder="Qty" name="quantity" aria-label="Product quantity" required>
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

<script>
  function setDeleteId(button) {
    const id = button.getAttribute('data-id');
    const deleteLink = document.getElementById('confirmDeleteBtn');
    deleteLink.href = `/delete_product?id=${id}`;
  }
</script>

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
        <a id="confirmDeleteBtn" href="#">
          <button type="button" class="btn btn-primary">Yes</button>
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      if (typeof jQuery === 'undefined') return;
  
      const table = $('.datatables-products').DataTable({
          searchDelay: 0,    
          minLength: 0      
      });
      $('#product-search').on('input', function() {
    clearTimeout(table.searchTimer);
    table.searchTimer = setTimeout(() => {
        table.column(2).search(this.value).draw();
    }, 100); 
});
$('#category-filter').on('change', function() {
        const dt = $('.datatables-products').DataTable();
        dt.column(3).search(this.value || '').draw();
    });
  });
  </script>

@endsection
