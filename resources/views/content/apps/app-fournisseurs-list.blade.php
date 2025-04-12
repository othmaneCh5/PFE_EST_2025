@extends('layouts/layoutMaster')

@section('title', 'Fournisseurs List - Pages')

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



@section('content')

{{-- <div class="row g-6 mb-6">
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
</div> --}}
<!-- Users List Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">Users</h5>
    @can('create fournisseurs')
      <button 
      type="button" 
      class="btn btn-primary" 
      data-bs-toggle="modal" 
      data-bs-target="#largeModal"> 
      <i class="ti ti-plus me-1"></i> Add Fournisseur
      </button>
    @endcan
        
    
      
  </div>
  



  <div class="card-datatable table-responsive">
    <table class="datatables-users table">
        <thead class="border-top">
            <tr>
                <th>Fournisseur</th>
                <th>Phone number</th>
                <th>Address</th>
                @canany(['edit users', 'delete users'])
                  <th>Actions</th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @foreach ($fournisseurs as $fournisseur)
                <tr>
                    <td>
                        <div class="d-flex justify-content-start align-items-center fournisseur-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar-sm me-4">
                                    @if ($fournisseur->profile_photo_path)
                                        <img src="{{ asset('storage/' . $fournisseur->profile_photo_path) }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        @php
                                            $states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                                            $state = $states[array_rand($states)];
                                            $initials = implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode(' ', $fournisseur->name)));
                                        @endphp
                                        <span class="avatar-initial rounded-circle bg-label-{{ $state }}">{{ $initials }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                {{-- <a href="{{ route('app-user-view', $user->id) }}" class="text-heading text-truncate"> --}}
                                    <span class="fw-medium">{{ $fournisseur->name }}</span>
                                {{-- </a> --}}
                                <small>{{ $fournisseur->email }}</small>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span class="text-heading">{{ $fournisseur->phone }}</span>
                    </td>
                    <td>
                        <span class="text-heading">{{ $fournisseur->address }}</span>
                    </td>
                    
                    <td>
                        <div class="d-flex align-items-center">
                        <button  
                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-user-btn"
                            data-bs-toggle="modal" 
                            data-bs-target="#editUserModal"
                            data-fournisseur-id="{{ $fournisseur->id }}"
                            data-fournisseur-name="{{ $fournisseur->name }}"
                            data-fournisseur-email="{{ $fournisseur->email }}"
                            data-fournisseur-phone="{{ $fournisseur->phone }}"
                            data-fournisseur-address="{{ $fournisseur->address }}"
                          >
                            <i class="ti ti-edit ti-md"></i>
                         </button>
                    
                          
                          
                         <button 
                         type="button" 
                         class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light" 
                         data-bs-toggle="modal" 
                         data-bs-target="#modalCenter"
                         data-id="{{ $fournisseur->id }}" {{-- Pass the ID here --}}
                         onclick="setDeleteId(this)"
                       >
                         <i class="ti ti-trash ti-md"></i> 
                     </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
  function setDeleteId(button) {
    const id = button.getAttribute('data-id');
    const deleteLink = document.getElementById('confirmDeleteBtn');
    deleteLink.href = `/delete_fournisseur?id=${id}`;
  }
</script>

  <!-- Offcanvas to add new user -->
</div>
<div class="modal fade" id="largeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> 
    <form action="/add_fournisseur" method="post">
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
            <label for="dobLarge" class="form-label">Address</label>
            <input type="text" name="address" id="dobLarge" class="form-control">
          </div>
        </div>
        
      <div class="row g-6" style="position: relative ; top:20px;">
        <div class="col mb-6">
          <label for="nameLarge" class="form-label">Phone Number</label>
          <input type="number" name="phone" id="nameLarge" class="form-control" placeholder="Enter Name">
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
      const fournisseurId = button.getAttribute('data-fournisseur-id');
      const fournisseurName = button.getAttribute('data-fournisseur-name');
      const fournisseurEmail = button.getAttribute('data-fournisseur-email');
      const fournisseurPhone = button.getAttribute('data-fournisseur-phone');
      const fournisseurAddress = button.getAttribute('data-fournisseur-address');

      // Update the form action URL
      const form = document.getElementById('editUserForm');
      form.action = `/edit_fournisseur/${fournisseurId}`;

      // Populate the form fields
      document.getElementById('editName').value = fournisseurName;
      document.getElementById('editEmail').value = fournisseurEmail;
      document.getElementById('editPhoneNumber').value = fournisseurPhone;
      document.getElementById('editAddress').value = fournisseurAddress;

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
              <label for="editAddress" class="form-label">Address</label>
              <input type="text" name="address" id="editAddress" class="form-control">
            </div>
          </div>
          
          <div class="row g-6" style="position: relative ; top:20px;">
            <div class="col mb-6">
              <label for="editPhoneNumber" class="form-label">Phone Number</label>
              <input type="number" name="phone" id="editPhoneNumber" class="form-control" placeholder="Enter Phone Number">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Fournisseur</button>
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
        <a id="confirmDeleteBtn" href="#">
          <button type="button" class="btn btn-primary">Yes</button>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
