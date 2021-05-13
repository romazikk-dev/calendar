<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('hall') }}
    </x-slot>
    
    <x-slot name="actions">
        <a class="btnn btn-sm btn-primary float-right page-action" href="{{ route('dashboard.hall.create') }}">
            Add
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
            var dataListRoute = "{{ route('dashboard.hall.data_list') }}";
            var toggleSuspension = "{{ route('dashboard.hall.toggle_suspension', ':id') }}";
            var showRoute = "{{ route('dashboard.hall.show', ':id') }}";
            var deleteRoute = '{{ route("dashboard.hall.destroy", ":id") }}';
            var editRoute = '{{ route("dashboard.hall.edit", ":id") }}';
            
            var csrf = '@csrf';
            var methodDelete = `@method('DELETE')`;
            
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/halls-list.js') }}?{{$rand}}"></script>
        
        
    </x-slot>
    
    <x-slot name="styles">
        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css"> -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
        <style>
            #dataTable .actions{
                width: 140px!important;
            }
            #dataTable .actions .action{
                margin-left: 6px;
            }
            #dataTable .coll-status{
                vertical-align: middle;
                text-align: center!important;
            }
            #dataTable .coll-status .status-circle{
                position: relative;
                left: 15px;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                position: relative;
                left: 15px;
            }
            #dataTable .workers-count div{
                position: relative;
                left: 13px;
                font-weight: bold;
            }
        </style>
    </x-slot>
    
    <div id="hallsList"></div>
    
</x-dashboard-layout>