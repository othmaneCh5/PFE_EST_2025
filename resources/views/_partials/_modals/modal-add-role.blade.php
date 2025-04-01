<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h4 class="role-title mb-2">Add New Role</h4>
          <p>Set role permissions</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-6" method="POST" action="/add_role">
          @csrf
          <div class="col-12">
            <label class="form-label" for="modalRoleName">Role Name</label>
            <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Enter a role name" />
          </div>
          <div class="col-12">
            <h5 class="mb-6">Role Permissions</h5>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <thead>
                  <tr>
                    <th colspan="2">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllPermissions" />
                        <label class="form-check-label fw-bold" for="selectAllPermissions">Select All (Administrator Access)</label>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-medium text-heading">Users Management</td>
                    <td>
                      <div class="d-flex justify-content-end">
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input" type="checkbox" id="viewUsers" name="permissions[]" value="view users" />
                          <label class="form-check-label" for="viewUsers">View</label>
                        </div>
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input" type="checkbox" id="editUsers" name="permissions[]" value="edit users" />
                          <label class="form-check-label" for="editUsers">Edit</label>
                        </div>
                        <div class="form-check mb-0 me-4 me-lg-12">
                          <input class="form-check-input" type="checkbox" id="deleteUsers" name="permissions[]" value="delete users" />
                          <label class="form-check-label" for="deleteUsers">Delete</label>
                        </div>
                        <div class="form-check mb-0">
                          <input class="form-check-input" type="checkbox" id="createUsers" name="permissions[]" value="create users" />
                          <label class="form-check-label" for="createUsers">Create</label>
                        </div>
                      </div>
                    </td>
                  </tr>
                    <tr>
                    <td class="text-nowrap fw-medium text-heading">Products Management</td>
                    <td>
                      <div class="d-flex justify-content-end">
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="viewproducts" name="permissions[]" value="view products" />
                        <label class="form-check-label" for="viewproducts">View</label>
                      </div>
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="editproducts" name="permissions[]" value="edit products" />
                        <label class="form-check-label" for="editproducts">Edit</label>
                      </div>
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="deleteproducts" name="permissions[]" value="delete products" />
                        <label class="form-check-label" for="deleteproducts">Delete</label>
                      </div>
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="createproducts" name="permissions[]" value="create products" />
                        <label class="form-check-label" for="createproducts">Create</label>
                      </div>
                      </div>
                    </td>
                    </tr>
                    <tr>
                    <td class="text-nowrap fw-medium text-heading">categories Management</td>
                    <td>
                      <div class="d-flex justify-content-end">
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="viewcategories" name="permissions[]" value="view categories" />
                        <label class="form-check-label" for="viewcategories">View</label>
                      </div>
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="editcategories" name="permissions[]" value="edit categories" />
                        <label class="form-check-label" for="editcategories">Edit</label>
                      </div>
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="deletecategories" name="permissions[]" value="delete categories" />
                        <label class="form-check-label" for="deletecategories">Delete</label>
                      </div>
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="createcategories" name="permissions[]" value="create categories" />
                        <label class="form-check-label" for="createcategories">Create</label>
                      </div>
                      </div>
                    </td>
                    </tr>
                    <tr>
                    <td class="text-nowrap fw-medium text-heading">roles Management</td>
                    <td>
                      <div class="d-flex justify-content-end">
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="viewroles" name="permissions[]" value="view roles" />
                        <label class="form-check-label" for="viewroles">View</label>
                      </div>
                      <div class="form-check mb-0 me-4 me-lg-12">
                        <input class="form-check-input" type="checkbox" id="editroles" name="permissions[]" value="edit roles" />
                        <label class="form-check-label" for="editroles">Edit</label>
                      </div>
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="deleteroles" name="permissions[]" value="delete roles" />
                        <label class="form-check-label" for="deleteroles">Delete</label>
                      </div>
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="updateroles" name="permissions[]" value="update roles" />
                        <label class="form-check-label" for="updateroles">Update</label>
                      </div>
                      </div>
                    </td>
                    </tr>
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-3">Submit</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->

<script>
  document.getElementById('selectAllPermissions').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
  });
</script>
