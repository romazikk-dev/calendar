<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('worker') }}
    </x-slot>
    
    <x-slot name="actions">
        <a class="btnn btn-sm btn-primary float-right page-action" href="{{ route('dashboard.worker.create') }}">
            New Worker
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
              <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
              <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
            </svg>
        </a>
    </x-slot>
    
    <x-slot name="scripts">        
        <!--  Datepikers  -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            $(document).ready(function (){
                var dateFormat = "dd-mm-yy",
                    from = $( "#from" ).datepicker({
                          defaultDate: "+1w",
                          changeMonth: true,
                          numberOfMonths: 1,
                          dateFormat: dateFormat,
                          minDate: new Date()
                    }).on( "change", function() {
                      to.datepicker("option", "minDate", getDate(this));
                    }),
                    to = $( "#to" ).datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 1,
                        dateFormat: dateFormat,
                        minDate: new Date()
                    }).on( "change", function() {
                        from.datepicker( "option", "maxDate", getDate( this ) );
                    });

                    function getDate( element ) {
                        var date;
                        try {
                            date = $.datepicker.parseDate( dateFormat, element.value );
                        } catch( error ) {
                            date = null;
                        }

                        return date;
                    }
            });
        </script>
        
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
                        $(row).attr('full-name', data.full_name);
                        $(row).attr('email', data.email);
                        $(row).attr('created-at', data.created_at);
                        $(row).attr('worker-suspension-id', data.worker_suspension_id);
                        $(row).attr('worker-suspension-from', data.worker_suspension_from);
                        $(row).attr('worker-suspension-to', data.worker_suspension_to);
                        if(data.worker_suspension_id !== null)
                            $(row).addClass('table-danger');
                    },
                    "columns": [
                        {data: 'id', name: 'id'},
                        {data: 'full_name', name: 'full_name'},
                        // {data: 'first_name', name: 'first_name', visible: false},
                        // {data: 'last_name', name: 'last_name', visible: false},
                        {data: 'email', name: 'email'},
                        {data: 'created_at', name: 'created_at'},
                        {
                            data: null,
                            className: "actions",
                            render: function ( data, type, row, meta ) {
                                // console.log(data);
                                
                                var delete_url = '{{ route("dashboard.worker.destroy", ":id") }}';
                                delete_url = delete_url.replace(':id', row.id);
                                
                                var edit_url = '{{ route("dashboard.worker.edit", ":id") }}';
                                edit_url = edit_url.replace(':id', row.id);
                                
                                var edit_password_url = '{{ route("dashboard.worker.edit_password", ":id") }}';
                                edit_password_url = edit_password_url.replace(':id', row.id);
                                
                                var show_url = '{{ route("dashboard.worker.show", ":id") }}';
                                show_url = show_url.replace(':id', row.id);
                                
                                
                                // let reinstate = '';
                                // if(data.worker_suspension_id !== null){
                                var toggle_suspension = '{{ route("dashboard.worker.toggle_suspension", ":id") }}';
                                toggle_suspension = toggle_suspension.replace(':id', row.id);
                                let stopSuspensionVisibility = data.worker_suspension_id !== null ? '' : 'd-none';
                                let reinstate = `
                                    <a href="#"
                                        onclick="
                                            event.preventDefault();
                                            if(confirm('Do you really want to stop suspension for this worker?')){
                                                toogleSuspension(${row.id}, 'disable');
                                            }
                                        "
                                        class="float-right stop-suspension-action action ${stopSuspensionVisibility}"
                                        title="Stop suspension">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                                          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                                        </svg>
                                    </a>
                                `;
                                // }
                                
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
                                
                                <a href="${edit_password_url}"
                                    class="float-right edit-password-action action"
                                    title="Change password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
                                    </svg>
                                </a>
                                
                                <a href="#"
                                    onclick="
                                        event.preventDefault();
                                        openModal(${row.id});
                                    "
                                    class="float-right toggle-suspension-action action"
                                    title="Suspention">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-circle-fill" viewBox="0 0 16 16">
                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5h-3z"/>
                                    </svg>
                                </a>
                                
                                ${reinstate}
                                
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
                        "url": "{{ route('dashboard.worker.data_list') }}",
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
        
        <!--  Modal  -->
        <script type="text/javascript">
            // $('#suspendModal').modal('show');
            
            function openModal(id){
                $('#suspendModal').modal('show');
                $('#suspendModal').attr('worker-id', id);
                setModalContent();
            }
            
            function setModalContent(){
                // $(`#dataTable tr[worker-id=${id}]`).attr(`worker-id`);
                let id = $('#suspendModal').attr('worker-id');
                let tr = $(`#dataTable tr[worker-id=${id}]`);
                let email = tr.attr(`email`);
                let workerSuspensionId = tr.attr(`worker-suspension-id`);
                let workerSuspensionFrom = tr.attr(`worker-suspension-from`);
                let workerSuspensionTo = tr.attr(`worker-suspension-to`);
                // console.log(workerSuspensionFrom);
                // console.log($('#suspendModal').attr('worker-suspension-id'));
                
                if(typeof workerSuspensionId != 'undefined' &&
                typeof workerSuspensionFrom != 'undefined' &&
                typeof workerSuspensionTo != 'undefined'){
                    $('#suspendModal .status').html(`
                        Worker with email - ${email}<br>
                        Suspended for period
                    `);
                    $('#periodSuspendBtn').addClass('d-none');
                    $('#disableSuspendBtn').removeClass('d-none');
                    $('#completeSuspendBtn').removeClass('d-none');
                    let workerSuspensionFromArr = workerSuspensionFrom.split("-");
                    workerSuspensionFrom = workerSuspensionFromArr[2] + '-' + workerSuspensionFromArr[1] + '-' + workerSuspensionFromArr[0];
                    let workerSuspensionToArr = workerSuspensionTo.split("-");
                    workerSuspensionTo = workerSuspensionToArr[2] + '-' + workerSuspensionToArr[1] + '-' + workerSuspensionToArr[0];
                    $('#from').val(workerSuspensionFrom);
                    $('#to').val(workerSuspensionTo);
                }else if(typeof workerSuspensionId != 'undefined' &&
                typeof workerSuspensionFrom == 'undefined' &&
                typeof workerSuspensionTo == 'undefined'){
                    $('#suspendModal .status').html(`
                        ${email}<br>
                        Totally suspended
                    `);
                    $('#completeSuspendBtn').addClass('d-none');
                    $('#periodSuspendBtn').removeClass('d-none');
                    $('#disableSuspendBtn').removeClass('d-none');
                    $('#from').val('');
                    $('#to').val('');
                }else if(typeof workerSuspensionId == 'undefined' &&
                typeof workerSuspensionFrom == 'undefined' &&
                typeof workerSuspensionTo == 'undefined'){
                    $('#suspendModal .status').html(`
                        ${email}<br>
                        Worker not suspended
                    `);
                    $('#completeSuspendBtn').removeClass('d-none');
                    $('#periodSuspendBtn').removeClass('d-none');
                    $('#disableSuspendBtn').addClass('d-none');
                    $('#from').val('');
                    $('#to').val('');
                }
            }
            
            $(document).on('click', '#completeSuspendBtn', function(e){
                e.preventDefault();
                let workerId = $('#suspendModal').attr('worker-id');
                toogleSuspension(workerId);
            });
            
            $(document).on('click', '#periodSuspendBtn', function(e){
                e.preventDefault();
                let workerId = $('#suspendModal').attr('worker-id');
                toogleSuspension(workerId, 'period');
            });
            
            $(document).on('click', '#disableSuspendBtn', function(e){
                e.preventDefault();
                let workerId = $('#suspendModal').attr('worker-id');
                toogleSuspension(workerId, 'disable');
            });
            
            function toogleSuspension(workerId, type = 'complete', data = {}){
                // let data = data;
                data.type = type;
                
                let url = '{{ route("dashboard.worker.toggle_suspension", ":id") }}';
                url = url.replace(':id', workerId);
                // let url = $('#suspendModal').attr('data-worker-id');
                
                if(type == 'period'){
                    let from = $("#from").val();
                    let to = $("#to").val();
                    
                    if(typeof from == 'undefined' || from == '' || typeof to == 'undefined' || to == ''){
                        alert('Please fill inputs `From` and `To`');
                        if(typeof from == 'undefined' || from == ''){
                            $("#from").focus();
                        }else if(typeof to == 'undefined' || to == ''){
                            $("#to").focus();
                        }
                        return;
                    }else{
                        data.from = from;
                        data.to = to;
                    }
                }
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: "post",
                    data: data,
                    success: function(data, textStatus, jqXHR ){
                        // console.log(data);
                        if(data.status == 'success' && (data.type == 'complete' || data.type == 'period')){
                            $('#disableSuspendBtn').removeClass('d-none');
                            $('#dataTable tr[worker-id=' + workerId + ']').addClass('table-danger');
                            $('#dataTable tr[worker-id=' + workerId + '] .stop-suspension-action').removeClass('d-none');
                        }else{
                            $('#disableSuspendBtn').addClass('d-none');
                            $('#dataTable tr[worker-id=' + workerId + ']').removeClass('table-danger');
                            $('#dataTable tr[worker-id=' + workerId + '] .stop-suspension-action').addClass('d-none');
                        }
                        let tr = $(`#dataTable tr[worker-id=${workerId}]`);
                        tr.attr('worker-suspension-id', data.suspension_id ?? null);
                        tr.attr('worker-suspension-from', data.from ?? null);
                        tr.attr('worker-suspension-to', data.to ?? null);
                        setModalContent();
                    },
                    error: function(data, textStatus, jqXHR ){
                        console.log(data);
                    },
                });
            }
        </script>
        
        
    </x-slot>
    
    <x-slot name="styles">
        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css"> -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
        <style>
            /* #dataTable .actions{
                min-width: 80px;
            }
            #dataTable .actions .edit-action{
                position: relative;
                left: -5px;
                margin-left: 10px;
            } */
            #dataTable .actions{
                width: 140px!important;
            }
            #dataTable .actions .action{
                margin-left: 6px;
            }
            /* #dataTable .actions .stop-action{
                height: 30px;
                width: 30px;
            } */
            /* #dataTable .actions .action:first-child{
                margin-left: 0px!important;
            } */
            /* edit-password-action */
        </style>
    </x-slot>

    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <!-- <th>First Name</th>
                <th>Last Name</th> -->
                <th>Email (login)</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    
    <!-- Modal -->
    <div class="modal fade" id="suspendModal" data-link="http://hairdressers.com/dashboard/worker/4/toggle-suspension" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suspension</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="status alert alert-info" role="alert">
                        This is a info alert—check it out!
                    </div>
                    <table>
                        <tbody>
                            <tr>
                                <td>From:</td>
                                <td>
                                    <input type="text" id="from" name="from" autocomplete="off" readonly="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td>To:</td>
                                <td>
                                    <input type="text" id="to" name="to" autocomplete="off" readonly="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="#" onclick="
                                        event.preventDefault();
                                        $('#from, #to').val('');
                                    ">Reset</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="disableSuspendBtn" type="button" class="btn btn-success d-none">Reinstate</button>
                    <button id="completeSuspendBtn" type="button" class="btn btn-info">Suspend completely</button>
                    <button id="periodSuspendBtn" type="button" class="btn btn-primary">Suspend for a period</button>
                </div>
            </div>
        </div>
    </div>
    
</x-dashboard-layout>