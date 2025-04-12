@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  ])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  ])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/app-access-roles.js',
  'resources/assets/js/modal-add-role.js',
  ])
@endsection


@section('content')
<style>
  footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: inherit; /* Keeps the existing background */
    z-index: 1000; /* Ensures it stays on top */
}
#tt{
  position: relative;
  left: 270px;
}
</style>
<h4 class="mb-1">Roles List</h4>

<p class="mb-6">A role provided access to predefined menus and features so that depending on <br> assigned role an administrator can have access to what user needs.</p>
<!-- Role cards -->
<div class="row g-6">
  @foreach ($roles as $role)
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-normal mb-0 text-body">Total {{ $role->users->count() }} users</h6>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                        @foreach ($role->users->take(4) as $user) <!-- Show up to 4 users -->
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar pull-up">
                                <img class="rounded-circle" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('storage/users/EBsOJ8DhABPIcKIWlra2A6kYq6qYIMQ7OaJZexND.png') }}" >
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="role-heading">
                        <h4 class="mb-1">{{ $role->name }}</h4>
                        
                    </div>
                    <a href="javascript:void(0);"><i class="ti ti-copy ti-md text-heading"></i></a>
                </div>
            </div>
        </div>
    </div>
@endforeach
  
 
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
      <div class="row h-100">
        <div class="col-sm-5">
          <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
            <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83">
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">Add New Role</button>
            <p class="mb-0"> Add new role, <br> if it doesn't exist.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Add Role Modal -->
@include('_partials/_modals/modal-add-role')
<!-- / Add Role Modal -->
@endsection
