@extends('layouts/layoutMaster')

@section('title', 'Permission - Apps')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss',
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
  'resources/assets/js/app-access-permission.js',
  'resources/assets/js/modal-add-permission.js',
  'resources/assets/js/modal-edit-permission.js',
  ])
@endsection

@section('content')


<!-- Permission Table -->
<div class="card">
  <div class="card-datatable table-responsive">
      <table class="table border-top">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Assigned To</th>
                  <th>Created Date</th>
              </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>
                    @foreach ($permission->users as $user)
                        <span class="badge bg-label-primary">{{ $user->name }}</span>
                    @endforeach
                    @foreach ($permission->roles as $role)
                        @foreach ($role->users as $user)
                            <span class="badge bg-label-secondary">{{ $user->name }} (via {{ $role->name }})</span>
                        @endforeach
                    @endforeach
                </td>
                <td>{{ $permission->created_at }}</td>
            </tr>
        @endforeach
        
          </tbody>
      </table>
  </div>
</div>

<!--/ Permission Table -->

<!-- Modal -->
@include('_partials/_modals/modal-add-permission')
@include('_partials/_modals/modal-edit-permission')
<!-- /Modal -->
@endsection
