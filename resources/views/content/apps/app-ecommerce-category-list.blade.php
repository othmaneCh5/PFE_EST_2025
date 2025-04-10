@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Category - Apps')

@section('vendor-style')
@vite([
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/vendor/libs/select2/select2.scss',
'resources/assets/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/vendor/libs/quill/typography.scss',
'resources/assets/vendor/libs/quill/katex.scss',
'resources/assets/vendor/libs/quill/editor.scss'
])
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-ecommerce.scss')
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/quill/katex.js',
  'resources/assets/vendor/libs/quill/quill.js'
  ])
@endsection

@section('page-script')
@vite('resources/assets/js/app-ecommerce-category-list.js')
@endsection

@section('content')
<div class="card-header d-flex justify-content-between align-items-center">
  <h5 class="card-title mb-0">Categories</h5>
  @can('create categories')
       <button 
  type="button" 
  class="btn btn-primary" 
  data-bs-toggle="modal" 
  data-bs-target="#enableOTP"> 
      <i class="ti ti-plus me-1"></i> Add Category
  </button> 
  @endcan
  
</div> 

<div class="app-ecommerce-category" style="position: relative;top : 15px;">
  <!-- Category List Table -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <table class="datatables-category-list table border-top">
        <thead>
          <tr>
            <th></th>
            <th>Categories</th>
            <th class="text-nowrap text-sm-end">Total Products</th>
            <th class="text-nowrap text-sm-end">Total Earnings</th>
            @can('edit categories' , 'delete categories')
              <th class="text-lg-center">Actions</th>
            @endcan
            
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr>
              <td></td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar-wrapper me-3 rounded-2 bg-label-secondary">
                    <div class="avatar">
                      @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Category-{{ $category->id }}" class="rounded-2">
                      @else
                        <span class="avatar-initial rounded-2 bg-label-primary">{{ substr($category->name, 0, 2) }}</span>
                      @endif
                    </div>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <span class="text-heading text-wrap fw-medium">{{ $category->name }}</span>
                    <span class="text-truncate mb-0 d-none d-sm-block"><small>{{ $category->description }}</small></span>
                  </div>
                </div>
              </td>
              <td class="text-sm-end">{{ $category->products_count }}</td>
              <td class="text-sm-end">{{ $category->total_earnings }}</td>
              <td class="text-lg-center">
                <div class="d-flex align-items-sm-center justify-content-sm-center">


                  <script>
                    document.addEventListener('DOMContentLoaded', function () {
  // Add event listeners to all edit buttons
  document.querySelectorAll('.edit-product-btn').forEach(button => {
    button.addEventListener('click', function () {
      // Get the category data from data attributes
      const categoryId = button.getAttribute('data-category-id');
      const categoryName = button.getAttribute('data-category-name');
      const categoryDescription = button.getAttribute('data-category-description');
      const categoryParentId = button.getAttribute('data-category-parent');
      const categoryImage = button.getAttribute('data-category-image');

      // Update the form action URL
      const form = document.getElementById('editCategoryForm');
      form.action = `/edit_category/${categoryId}`;

      // Populate the form fields
      document.getElementById('edit-category-name').value = categoryName;
      document.getElementById('edit-category-description').value = categoryDescription;
      document.getElementById('edit-category-parent').value = categoryParentId;

      // Update the image preview
      const imagePreview = document.getElementById('edit-category-image-preview');
      if (categoryImage) {
        imagePreview.src = `/storage/${categoryImage}`;
        imagePreview.style.display = 'block';
      } else {
        imagePreview.style.display = 'none';
      }
    });
  });
});
                  </script>

                  @can('edit categories')
                  <button  
                  class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-product-btn"
                  data-bs-toggle="modal" 
                  data-bs-target="#editCategoryModal"
                  data-category-id="{{ $category->id }}"
                  data-category-name="{{ $category->name }}"
                  data-category-description="{{ $category->description }}"
                  data-category-parent="{{ $category->parent_id }}"
                  data-category-image="{{ $category->image }}"
                  >
                  <i class="ti ti-edit ti-md"></i>
                  </button>
                  @endcan
                  @can('delete categories')
                  <button 
                      type="button" 
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light" 
                      data-bs-toggle="modal" 
                      data-bs-target="#modalCenter"
                      data-id="{{ $category->id }}" {{-- Pass the ID here --}}
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

  <!-- Offcanvas to add new category -->
</div>

    <div class="modal fade" id="enableOTP" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 class="mb-2">Add a new category</h4>
            </div>
            <form action="/add_category" method="POST" enctype="multipart/form-data">
              @csrf
            <div class="mb-6">
                <label class="form-label" for="ecommerce-product-name">Name</label>
                <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Product title" name="name" aria-label="Product title" required>
            </div>
            
                
            <div class="mb-6">
                <label class="form-label" for="category-org">
                  <span>Category</span>
              </label>
              <select name="parent_id" id="category-org" class="select2 form-select" data-placeholder="Select Category">
                  <option value="">Select Category</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach 
              </select>
            </div>
            
            <!-- Description -->
            <div>
                <label class="mb-1">Description (Optional)</label>
                <textarea class="form-control" name="description" id="ecommerce-category-description" rows="4"></textarea>
            </div>
            
            <div class="mb-4">
              <label for="formFile" class="form-label">category image</label>
              <input class="form-control" type="file" id="formFile" name="image">
            </div>
             
              <div class="col-12">
                <button type="submit" class="btn btn-primary me-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center mb-6">
              <h4 class="mb-2">Edit Category</h4>
            </div>
            <form id="editCategoryForm" action="/edit_category" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id" id="edit-category-id">
              
              <div class="mb-6">
                <label class="form-label" for="ecommerce-product-name">Name</label>
                <input type="text" class="form-control" id="edit-category-name" placeholder="Category name" name="name" aria-label="Category name" required>
              </div>
              
              <div class="mb-6">
                <label class="form-label" for="category-org">Parent Category</label>
                <select name="edit-category-parent" id="category-org" class="select2 form-select" data-placeholder="Select Category">
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach 
                </select>
              </div>
              
              <div>
                <label class="mb-1">Description (Optional)</label>
                <textarea class="form-control" name="description" id="edit-category-description" rows="4"></textarea>
              </div>
              
              <div class="mb-4">
                <label for="formFile" class="form-label">Category Image</label>
                <input class="form-control" type="file" id="formFile" name="image">
              </div>
              
              <!-- Image Preview -->
              <div class="mb-4">
                <img id="edit-category-image-preview" src="" alt="Category Image" style="max-width: 100px; display: none;">
              </div>
              
              <div class="col-12">
                <button type="submit" class="btn btn-primary me-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
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
        deleteLink.href = `/delete_category?id=${id}`;
      }
    </script>
    
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <div class="modal-body">
            
            <h5 class="modal-title" id="modalCenterTitle">Are you sure you want to delete the category ?</h5>
            
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

@endsection