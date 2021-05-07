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
        <!--  Datepikers  -->
        <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
        <!-- <script type="text/javascript">
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
        </script> -->
        
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
            
            
            
            // var delete_url = '{{ route("dashboard.hall.destroy", ":id") }}';
            // delete_url = delete_url.replace(':id', row.id);
            
            // var edit_url = '{{ route("dashboard.hall.edit", ":id") }}';
            // edit_url = editRoute.replace(':id', row.id);
            
            // var show_url = '{{ route("dashboard.hall.show", ":id") }}';
            // show_url = show_url.replace(':id', row.id);
            
            
            
            
            
            
            
            // var methodDelete = 111;
            
            //Initialising DataTable
            $(document).ready(function (){
                
            });
        </script>
        
        <!-- <script type="text/javascript" charset="utf8" src="{{ asset('js/dashboard/halls/dashboard-halls-index.js') }}?@php echo rand(100, 1000) @endphp"></script> -->
        
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/halls-list.js') }}?{{$rand}}"></script>
        
        <!--  Modal  -->
        <script type="text/javascript">
            // $('#suspendModal').modal('show');
            
            // function openModal(id){
            //     $('#suspendModal').modal('show');
            //     $('#suspendModal').attr('worker-id', id);
            //     setModalContent();
            // }
            
            function openInfoModal(id){
                $('#infoModal').modal('show');
                // $('#suspendModal').attr('worker-id', id);
                // setModalContent();
            }
            
            // function setModalContent(){
            //     // $(`#dataTable tr[worker-id=${id}]`).attr(`worker-id`);
            //     let id = $('#suspendModal').attr('worker-id');
            //     let tr = $(`#dataTable tr[worker-id=${id}]`);
            //     let email = tr.attr(`email`);
            //     let workerSuspensionId = tr.attr(`worker-suspension-id`);
            //     let workerSuspensionFrom = tr.attr(`worker-suspension-from`);
            //     let workerSuspensionTo = tr.attr(`worker-suspension-to`);
            //     // console.log(workerSuspensionFrom);
            //     // console.log($('#suspendModal').attr('worker-suspension-id'));
            // 
            //     if(typeof workerSuspensionId != 'undefined' &&
            //     typeof workerSuspensionFrom != 'undefined' &&
            //     typeof workerSuspensionTo != 'undefined'){
            //         $('#suspendModal .status').html(`
            //             Worker with email - ${email}<br>
            //             Suspended for period
            //         `);
            //         $('#periodSuspendBtn').addClass('d-none');
            //         $('#disableSuspendBtn').removeClass('d-none');
            //         $('#completeSuspendBtn').removeClass('d-none');
            //         let workerSuspensionFromArr = workerSuspensionFrom.split("-");
            //         workerSuspensionFrom = workerSuspensionFromArr[2] + '-' + workerSuspensionFromArr[1] + '-' + workerSuspensionFromArr[0];
            //         let workerSuspensionToArr = workerSuspensionTo.split("-");
            //         workerSuspensionTo = workerSuspensionToArr[2] + '-' + workerSuspensionToArr[1] + '-' + workerSuspensionToArr[0];
            //         $('#from').val(workerSuspensionFrom);
            //         $('#to').val(workerSuspensionTo);
            //     }else if(typeof workerSuspensionId != 'undefined' &&
            //     typeof workerSuspensionFrom == 'undefined' &&
            //     typeof workerSuspensionTo == 'undefined'){
            //         $('#suspendModal .status').html(`
            //             ${email}<br>
            //             Totally suspended
            //         `);
            //         $('#completeSuspendBtn').addClass('d-none');
            //         $('#periodSuspendBtn').removeClass('d-none');
            //         $('#disableSuspendBtn').removeClass('d-none');
            //         $('#from').val('');
            //         $('#to').val('');
            //     }else if(typeof workerSuspensionId == 'undefined' &&
            //     typeof workerSuspensionFrom == 'undefined' &&
            //     typeof workerSuspensionTo == 'undefined'){
            //         $('#suspendModal .status').html(`
            //             ${email}<br>
            //             Worker not suspended
            //         `);
            //         $('#completeSuspendBtn').removeClass('d-none');
            //         $('#periodSuspendBtn').removeClass('d-none');
            //         $('#disableSuspendBtn').addClass('d-none');
            //         $('#from').val('');
            //         $('#to').val('');
            //     }
            // }
            
            // $(document).on('click', '#completeSuspendBtn', function(e){
            //     e.preventDefault();
            //     let workerId = $('#suspendModal').attr('worker-id');
            //     toogleSuspension(workerId);
            // });
            
            // $(document).on('click', '#periodSuspendBtn', function(e){
            //     e.preventDefault();
            //     let workerId = $('#suspendModal').attr('worker-id');
            //     toogleSuspension(workerId, 'period');
            // });
            
            $(document).on('click', '#disableSuspendBtn', function(e){
                e.preventDefault();
                let workerId = $('#suspendModal').attr('worker-id');
                toogleSuspension(workerId, 'disable');
            });
            
            function toogleSuspension(workerId, type = 'complete', data = {}){
                // let data = data;
                data.type = type;
                
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
            
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
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
            #dataTable{
                
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
            #dataTable .coll-status .status-circle-opened{
                background-color: green;
            }
            #dataTable .coll-status .status-circle-closed{
                background-color: red;
            }
            #dataTable .workers-count div{
                position: relative;
                left: 13px;
                font-weight: bold;
            }
            // #dataTable td.coll-id,
            // #dataTable th.coll-id{
            //     width: 50px;
            // }
        </style>
    </x-slot>
    
    <div id="hallsList"></div>
    
    <!--
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>
                    Title
                </th>
                <th data-toggle="tooltip" data-placement="auto" title="Status of hall (closed/opened)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                        <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                    </svg>
                </th>
                <th data-toggle="tooltip" data-placement="auto" title="Number of employees">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                </th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    -->
    
    <!-- Modal -->
    <div class="modal fade" id="suspendModal" data-link="http://hairdressers.com/dashboard/worker/4/toggle-suspension" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Worker suspension</h5>
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
                    <button id="completelySuspendEmployeeBtn" type="button" class="btn btn-info">Suspend completely</button>
                    <button id="periodSuspendBtn" type="button" class="btn btn-primary">Suspend for a period</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade modal-custom-dark-header-footer" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <td>Description</td>
                            <td id="infoDescription"></td>
                        </tr>
                        <tr>
                            <td>Short description</td>
                            <td id="infoShortDescription"></td>
                        </tr>
                        <tr>
                            <td>Short description</td>
                            <td id="infoShortDescription"></td>
                        </tr>
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