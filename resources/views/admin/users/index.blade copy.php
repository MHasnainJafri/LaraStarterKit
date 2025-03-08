<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable with Bulk Actions</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }
        .select-all {
            margin-left: 5px;
        }
        .bulk-actions {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="bulk-actions">
        <select id="bulkActions">
            <option value="">Bulk Actions</option>
            <option value="delete">Delete Selected</option>
        </select>
    </div>

    <table id="userTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" class="select-all"></th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated dynamically -->
        </tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        // Initialize DataTable with checkboxes and bulk actions
        function initDataTable(selector, ajaxUrl, columns, options = {}) {
            if ($.fn.DataTable.isDataTable(selector)) {
                $(selector).DataTable().destroy();
            }

            // Add checkbox column at the beginning
            columns.unshift({
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="row-checkbox" value="${row.id}">`;
                }
            });

            // Default settings
            const defaults = {
                processing: true,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    type: 'GET',
                    data: function(d) {
                        d.customFilters = $(selector).data('customFilters') || {};
                    }
                },
                columns: columns,
                order: options.initialOrder || [[1, 'asc']],
                searching: true,
                paging: true,
                pageLength: options.pageLength || 10,
                dom: 'Bfrtip',
                buttons: options.buttons || [],
                language: {
                    emptyTable: options.emptyMessage || 'No data available'
                }
            };

            const table = $(selector).DataTable($.extend(true, {}, defaults, options));

            // Handle Select All checkbox
            $(selector).on('click', '.select-all', function () {
                const checked = $(this).prop('checked');
                $(selector).find('.row-checkbox').prop('checked', checked);
            });

            return table;
        }

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
                    { data: 'created_at', name: 'created_at', render: data => data ? new Date(data).toLocaleDateString() : 'N/A' }
                ], {
                    // buttons: ['copy', 'csv', 'excel', 'print'],
                    pageLength: 25,
                    emptyMessage: 'No records found'
                });
            }
        });
    </script>
</body>
</html>