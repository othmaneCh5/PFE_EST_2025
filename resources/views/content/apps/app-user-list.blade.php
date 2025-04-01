@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
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
@vite('resources/assets/js/app-user-list.js')
@endsection

@section('content')

<div class="row g-6 mb-6">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Session</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">21,459</h4>
              <p class="text-success mb-0">(+29%)</p>
            </div>
            <small class="mb-0">Total Users</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="ti ti-users ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Paid Users</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">4,567</h4>
              <p class="text-success mb-0">(+18%)</p>
            </div>
            <small class="mb-0">Last week analytics </small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="ti ti-user-plus ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Active Users</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">19,860</h4>
              <p class="text-danger mb-0">(-14%)</p>
            </div>
            <small class="mb-0">Last week analytics</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="ti ti-user-check ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span class="text-heading">Pending Users</span>
            <div class="d-flex align-items-center my-1">
              <h4 class="mb-0 me-2">237</h4>
              <p class="text-success mb-0">(+42%)</p>
            </div>
            <small class="mb-0">Last week analytics</small>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="ti ti-user-search ti-26px"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Users List Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">Users</h5>
    @can('create users')
        <button 
      type="button" 
      class="btn btn-primary" 
      data-bs-toggle="modal" 
      data-bs-target="#largeModal"> 
      <i class="ti ti-plus me-1"></i> Add User
      </button>
    @endcan
      
  </div>
  



  <div class="card-datatable table-responsive">
    <table class="datatables-users table">
        <thead class="border-top">
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Phone number</th>
                <th>Date f birth</th>
                <th>Status</th>
                @canany(['edit users', 'delete users'])
                  <th>Actions</th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <div class="d-flex justify-content-start align-items-center user-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar-sm me-4">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        @php
                                            $states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                                            $state = $states[array_rand($states)];
                                            $initials = implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode(' ', $user->name)));
                                        @endphp
                                        <span class="avatar-initial rounded-circle bg-label-{{ $state }}">{{ $initials }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                {{-- <a href="{{ route('app-user-view', $user->id) }}" class="text-heading text-truncate"> --}}
                                    <span class="fw-medium">{{ $user->name }}</span>
                                {{-- </a> --}}
                                <small>{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span  class="text-truncate d-flex align-items-center text-heading">
                            @if ($user->roles->first()->name == 'adminastrator')
                              <span class="badge bg-danger bg-glow">adminastrator</span>
                            @else
                              <span class="badge bg-warning bg-glow">{{ $user->roles->first()->name }}</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="text-heading">{{ $user->phone_number }}</span>
                    </td>
                    <td>
                        <span class="text-heading">{{ $user->dob }}</span>
                    </td>
                    <td>
                        @php
                            $statusObj = [
                                1 => ['title' => 'Pending', 'class' => 'bg-label-warning'],
                                2 => ['title' => 'Active', 'class' => 'bg-label-success'],
                                3 => ['title' => 'Inactive', 'class' => 'bg-label-secondary']
                            ];
                            $status = $statusObj[$user->status] ?? ['title' => 'Unknown', 'class' => 'bg-label-secondary'];
                        @endphp
                        <span class="badge {{ $status['class'] }}" text-capitalized>{{ $status['title'] }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                      @can('edit categories')
                        <button  
                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-user-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#editUserModal"
                            data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                            data-user-email="{{ $user->email }}"
                            data-user-phone="{{ $user->phone_number }}"
                            data-user-dob="{{ $user->dob }}"
                            data-user-status="{{ $user->status }}"
                            data-user-role="{{ $user->role }}"
                            data-user-image="{{ $user->profile_photo_path }}" 
                          >
                            <i class="ti ti-edit ti-md"></i>
                         </button>
                      @endcan
                          
                            {{-- <a href="{{ route('app-user-view', $user->id) }}" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"> --}}
                                {{-- <i class="ti ti-eye ti-md"></i>
                            </a> --}}
                          @can('delete categories')
                            <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light " data-bs-toggle="modal" data-bs-target="#modalCenter">
                              <i class="ti ti-trash ti-md"></i> 
                            </button>
                          @endcan
                            
                            {{-- <div class="dropdown-menu dropdown-menu-end m-0">
                                <a href="javascript:;" class="dropdown-item">Edit</a>
                                <a href="javascript:;" class="dropdown-item">Suspend</a>
                            </div> --}}
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


  <!-- Offcanvas to add new user -->
</div>
<div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> 
    <form action="/add_user" method="post" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="text-align: center; font-size: 1.5em; margin: 0 auto; display: block;" id="exampleModalLabel3">add a new user</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-6">
          <div class="col mb-6">
            <label for="nameLarge" class="form-label">Name</label>
            <input type="text" name="name" id="nameLarge" class="form-control" placeholder="Enter Name">
          </div>
          <div class="col mb-6">
            <label for="nameLarge" class="form-label">password</label>
            <input type="password" name="password" id="nameLarge" class="form-control" placeholder="Enter Name">
          </div>
        </div>
        <div class="row g-6">
          <div class="col mb-0">
            <label for="emailLarge" class="form-label">Email</label>
            <input type="email" name="email" id="emailLarge" class="form-control" placeholder="xxxx@xxx.xx">
          </div>
          <div class="col mb-0">
            <label for="dobLarge" class="form-label">DOB</label>
            <input type="date" name="dob" id="dobLarge" class="form-control">
          </div>
        </div>
        <div class="row g-6" style="position: relative ; top:20px;">
        <div class=" col mb-6">
          <label for="edit-product-image" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="edit-product-image" name="profile_photo_path">
          
        </div>
        <div class="mb-6 col ecommerce-select2-dropdown">
          <label class="form-label mb-1" for="editRole">
            <span>Role</span>
          </label>
          <select name="role" id="editRole" class="select2 form-select" data-placeholder="Select Role">
            <option value="">Select Role</option>
            
            @foreach($roles as $role)
              <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach 
          </select>
        </div>
      </div>
      <div class="row g-6" style="position: relative ; top:20px;">
        <div class="col mb-6">
          <label for="nameLarge" class="form-label">Phone Number</label>
          <input type="number" name="phone_number" id="nameLarge" class="form-control" placeholder="Enter Name">
        </div>
        <div class="mb-6 col ecommerce-select2-dropdown">
          <label class="form-label mb-1" for="category-org">
              <span>Status</span>
          </label>
          <select name="status" id="category-org" class="select2 form-select" data-placeholder="Select Category">
              <option value="">Select Status</option>
              <option value="hl">vdf Status</option>
              {{-- @foreach($categories as $category) --}}
                  {{-- <option value="{{ $category->id }}">{{ $category->name }}</option> --}}
              {{-- @endforeach  --}}
          </select>
      </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Add event listeners to all edit buttons
  document.querySelectorAll('.edit-user-btn').forEach(button => {
    button.addEventListener('click', function () {
      // Get the user data from data attributes
      const userId = button.getAttribute('data-user-id');
      const userName = button.getAttribute('data-user-name');
      const userEmail = button.getAttribute('data-user-email');
      const userPhone = button.getAttribute('data-user-phone');
      const userDob = button.getAttribute('data-user-dob');
      const userStatus = button.getAttribute('data-user-status');
      const userRole = button.getAttribute('data-user-role');
      const userImage = button.getAttribute('data-user-image');

      // Update the form action URL
      const form = document.getElementById('editUserForm');
      form.action = `/edit_user/${userId}`;

      // Populate the form fields
      document.getElementById('editName').value = userName;
      document.getElementById('editEmail').value = userEmail;
      document.getElementById('editPhoneNumber').value = userPhone;
      document.getElementById('editDob').value = userDob;
      document.getElementById('editStatus').value = userStatus;
      document.getElementById('editRole').value = userRole;

      // Update the image preview
      const imagePreview = document.getElementById('currentProfilePhoto');
      if (userImage) {
        imagePreview.src = `/storage/${userImage}`;
        imagePreview.style.display = 'block';
      } else {
        imagePreview.style.display = 'none';
      }
    });
  });
});
</script>


<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> 
    <form id="editUserForm" method="POST"  enctype="multipart/form-data">
      @csrf
      @method('PUT')...............;
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="text-align: center; font-size: 1.5em; margin: 0 auto; display: block;" id="exampleModalLabel3">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-6">
            <div class="col mb-6">
              <label for="editName" class="form-label">Name</label>
              <input type="text" name="name" id="editName" class="form-control" placeholder="Enter Name">
            </div>
            <div class="col mb-6">
              <label for="editPassword" class="form-label">Password</label>
              <input type="password" name="password" id="editPassword" class="form-control" placeholder="Leave blank to keep current">
            </div>
          </div>
          <div class="row g-6">
            <div class="col mb-0">
              <label for="editEmail" class="form-label">Email</label>
              <input type="email" name="email" id="editEmail" class="form-control" placeholder="xxxx@xxx.xx">
            </div>
            <div class="col mb-0">
              <label for="editDob" class="form-label">DOB</label>
              <input type="date" name="dob" id="editDob" class="form-control">
            </div>
          </div>
          <div class="row g-6" style="position: relative ; top:20px;">
            <div class="col mb-6">
              <label for="editProfilePhoto" class="form-label">Profile Photo</label>
              <input type="file" class="form-control" id="editProfilePhoto" name="profile_photo_path">
              <div class="mt-2">
                <img id="currentProfilePhoto" src="" alt="Current profile photo" style="max-width: 100px; max-height: 100px;">
              </div>
            </div>
            <div class="mb-6 col ecommerce-select2-dropdown">
              <label class="form-label mb-1" for="editRole">
                <span>Role</span>
              </label>
              <select name="role" id="editRole" class="select2 form-select" data-placeholder="Select Role">
                <option value="">Select Role</option>
                
                @foreach($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach 
              </select>
            </div>
          </div>
          <div class="row g-6" style="position: relative ; top:20px;">
            <div class="col mb-6">
              <label for="editPhoneNumber" class="form-label">Phone Number</label>
              <input type="number" name="phone_number" id="editPhoneNumber" class="form-control" placeholder="Enter Phone Number">
            </div>
            <div class="mb-6 col ecommerce-select2-dropdown">
              <label class="form-label mb-1" for="editStatus">
                <span>Status</span>
              </label>
              <select name="status" id="editStatus" class="select2 form-select" data-placeholder="Select Status">
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <div class="modal-body">
        
        <h5 class="modal-title" id="modalCenterTitle">Are you sure you want to delete the user ?</h5>
        
      </div> 
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="/delete_user?id={{ $user->id }}">
          <button type="button" class="btn btn-primary">Yes</button>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
