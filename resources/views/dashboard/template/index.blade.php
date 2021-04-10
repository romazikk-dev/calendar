<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('template') }}
    </x-slot>
    
    <x-slot name="actions">
        <a class="btnn btn-sm btn-primary float-right page-action" href="{{ route('dashboard.template.create') }}">
            New Template
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="scripts">
        <!--  Datatable  -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            //Initialising DataTable
            $(document).ready(function (){
                $('#dataTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [[ 0, "desc" ]],
                    "createdRow": function(row, data, dataIndex){
                        $(row).attr('worker-id', data.id);
                        $(row).attr('is-closed', data.is_closed);
                        $(row).attr('workers-count', data.title);
                        $(row).attr('created-at', data.duration);
                        if(data.is_closed == 1)
                            $(row).addClass('table-danger');
                    },
                    "columns": [
                        {data: 'id', name: 'id'},
                        {
                            data: 'title',
                            name: 'title',
                        },
                        {data: 'duration', name: 'duration'},
                        {data: 'created_at', name: 'created_at'},
                        {
                            data: null,
                            className: "actions",
                            render: function ( data, type, row, meta ) {
                                var delete_url = '{{ route("dashboard.template.destroy", ":id") }}';
                                delete_url = delete_url.replace(':id', row.id);
                                
                                var edit_url = '{{ route("dashboard.template.edit", ":id") }}';
                                edit_url = edit_url.replace(':id', row.id);
                                
                                var show_url = '{{ route("dashboard.template.show", ":id") }}';
                                show_url = show_url.replace(':id', row.id);
                                
                                return `
                                <form method="post" action="${delete_url}">
                                    @csrf
                                    @method('DELETE')
                                    <a href="${delete_url}"
                                        onclick="event.preventDefault(); if(confirm('Do you realy want to delete this worker')){ this.closest('form').submit();}"
                                        class="float-right delete-action action"
                                        data-toggle="tooltip"
                                        title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                        </svg>
                                    </a>
                                </form>
                                
                                <a href="${edit_url}"
                                    class="float-right edit-action action"
                                    data-toggle="tooltip"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </a>
                                
                                <a href="${show_url}"
                                    class="float-right show-action action"
                                    data-toggle="tooltip"
                                    title="Show">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                      <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                      <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                    </svg>
                                </a>`;
                            },
                            sortable: false,
                            searchable: false,
                        }
                    ],
                    "columnDefs": [
                        {
                            "targets": [3],
                            "render": function ( data, type, row, meta ) {
                                let date = new Date(data);
                                
                                let dateOfMonth = date.getDate();
                                dateOfMonth = dateOfMonth < 10 ? '0' + dateOfMonth.toString() : dateOfMonth;
                                
                                let month = date.getMonth();
                                month = month < 10 ? '0' + month.toString() : month;
                                
                                return dateOfMonth + '/' + month + '/' + date.getFullYear();
                            }
                        }
                    ],
                    // "ajax": "{{ route('dashboard.worker.data_list') }}",
                    "ajax": {
                        "url": "{{ route('dashboard.template.data_list') }}",
                        "type": "post",
                        "headers": {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        // "data": {
                        //     // "user_id": 451
                        // }
                    }
                });
            });
        </script>
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
        <style>
            #dataTable .actions{
                width: 140px!important;
            }
            #dataTable .actions .action{
                margin-left: 6px;
            }
        </style>
    </x-slot>

    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
</x-dashboard-layout>