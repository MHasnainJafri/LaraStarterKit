@extends('admin.layouts.app')
@section('title', 'Dashboard - Admin Panel')
@section('meta_description', 'Overview of platform analytics and management tools.')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h2 class="page-title">
              Users
            </h2>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
      
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Users</h4>
        <div class="bulk-actions d-flex">
            <select id="bulkActions" class="form-select me-2">
                <option value="">Bulk Actions</option>
                <option value="delete">Delete Selected</option>
            </select>
            <button id="applyAction" class="btn btn-danger">Apply</button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="userTable" class="table table-hover w-100">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
      </div>
    </div>
   
  </div>
@endsection
@push('scripts')
<!-- jQuery, Bootstrap, DataTables -->


<script>
  

    $(document).ready(function () {
        // Add bulk action dropdown
        $('#bulkActions').on('change', function () {
            const selectedAction = $(this).val();
            const selectedRows = $('.row-checkbox:checked').map(function () {
                return $(this).val();
            }).get();

            if (selectedRows.length === 0) {
                alert('No rows selected!');
                return;
            }

            if (selectedAction === 'delete') {
                if (confirm('Are you sure you want to delete selected rows?')) {
                    $.post('/admin/bulk-delete', { ids: selectedRows }, function (response) {
                        $('#userTable').DataTable().ajax.reload();
                    });
                }
            }
        });

        if ($('#userTable').length) {
            initDataTable('#userTable', '/admin/get-data', [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at', render: data => data ? new Date(data).toLocaleDateString() : 'N/A' },
                { data: null, name: 'actions', orderable: false, searchable: false,render: data => `<a href="/admin/users/${data.id}" class="btn btn-sm btn-primary">Edit</a> <a href="/admin/users/${data.id}/delete" class="btn btn-sm btn-danger">Delete</a>` },
           
            ], {
                buttons: ['copy', 'csv', 'excel', 'print'],
                pageLength: 25,
                emptyMessage: 'No records found'
            });
        }
    });
</script>
@endpush
