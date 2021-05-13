<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('client') }}
    </x-slot>
    
    <x-slot name="actions">
        <a class="btnn btn-sm btn-primary float-right page-action" href="{{ route('dashboard.client.create') }}">
            Add
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="scripts">
        
        <script type="text/javascript">
            var dataListRoute = "{{ route('dashboard.client.data_list') }}";
            var toggleSuspension = "{{ route('dashboard.client.toggle_suspension', ':id') }}";
            var showRoute = "{{ route('dashboard.client.show', ':id') }}";
            var deleteRoute = '{{ route("dashboard.client.destroy", ":id") }}';
            var editRoute = '{{ route("dashboard.client.edit", ":id") }}';
            
            // console.log(toggleSuspension);
            
            var csrf = '@csrf';
            var methodDelete = `@method('DELETE')`;
        </script>
        
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/client/clients-list.js') }}?{{$rand}}"></script>
        
    </x-slot>
    
    <x-slot name="styles">
        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css"> -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    </x-slot>
    
    <div id="clientsList"></div>
    
</x-dashboard-layout>