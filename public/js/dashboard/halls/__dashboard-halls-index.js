$(document).ready(function(){
    
    (new function(){
        
        this.suspendModalId = "suspendModal";
        this.dataTableId = "dataTable";
        
        this.init = function(){
            
            this.initDataTable();
            this.regBtns();
            // this.regEmployeeSuspensionButton();
            // this.regEmployeeSuspensionButton();
        }
        
        this.regBtns = function(){
            this.regEmployeeSuspensionBtn();
            this.regCompletelySuspendEmployeeBtn();
            this.regMoreInfoBtn();
        }
        
        // function openInfoModal(id){
        //     $('#infoModal').modal('show');
        //     // $('#suspendModal').attr('worker-id', id);
        //     // setModalContent();
        // }
        
        this.regMoreInfoBtn = function(){
            onClick(15);
            
            $(document).on('click', '#moreInfoBtn', (e) => {
                e.preventDefault();
                let id = parseInt($(e.target).closest('tr').attr('id'));
                onClick(id);
                
                // url = showRoute.replace(':id', id);
                // 
                // $.ajax({
                //     type: "get",
                //     url: url,
                //     // data: formdata,
                //     headers: {          
                //         Accept: "application/json",         
                //         "Content-Type": "application/json"   
                //     },
                //     success: function (response) {
                //     	console.log(response);
                //         $("body").find('#infoModal').modal('show');
                //         // $('#infoModal').attr('worker-id', id);
                //     }
                // });
                // $('#infoModal').attr('worker-id', id);
                // let workerId = $('#suspendModal').attr('worker-id');
                // alert(111);
                // let workerId = $('#suspendModal').attr('worker-id');
                // this.toogleSuspension(workerId);
            });
            
            function onClick(id){
                url = showRoute.replace(':id', id);
                
                $.ajax({
                    type: "get",
                    url: url,
                    // data: formdata,
                    headers: {          
                        Accept: "application/json",         
                        "Content-Type": "application/json"   
                    },
                    success: function (response) {
                        let modal = $("body").find('#infoModal');
                        
                        // $("body").find('#infoModal .modal-title").text('ddddddd');
                        // setTimeout(() => {
                        //     $("body").find('#infoModal .modal-title').text('ddddddd');
                        // }, 1000);
                        
                        // alert(1111);
                        
                    	console.log(response.title);
                        modal.find('.modal-title').text(response.title);
                        $("body").find('#infoModal').modal('show');
                        
                        // $('#infoModal').modal('show');
                        
                        // modal.find(".modal-header .modal-title").text(response.title);
                        // $('#infoModal').attr('worker-id', id);
                    }
                });
            }
        }
        
        this.regCompletelySuspendEmployeeBtn = function(){
            $(document).on('click', '#completelySuspendEmployeeBtn', (e) => {
                e.preventDefault();
                let workerId = $('#suspendModal').attr('worker-id');
                this.toogleSuspension(workerId);
            });
        }
        
        this.toogleSuspension = function(workerId, type = 'completely', data = {}){
            // let data = data;
            data.type = type;
            
            let url = toggleClosedRoute;
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
        
        this.regEmployeeSuspensionBtn = function(){
            $('body').on('click', '#employeeSuspensionBtn', (e) => {
                e.preventDefault();
                let id = $(e.target).closest('tr').attr('worker-id');
                $('#suspendModal').attr('worker-id', id);
                setModalContent();
                $('#suspendModal').modal('show');
            });
            
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
        }
        
        this.initDataTable = function(){
            let _this = this;
            $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                // "order": [[ 1, "asc" ]],
                "order": [],
                "createdRow": function(row, data, dataIndex){
                    $(row).attr('id', data.id);
                    $(row).attr('is-closed', data.is_closed);
                    $(row).attr('workers-count', data.workers_count);
                    $(row).attr('created-at', data.created_at);
                    if(data.is_closed == 1)
                        $(row).addClass('table-danger');
                },
                "columns": [
                    {
                        data: 'id',
                        name: 'id',
                        className: 'coll-id',
                        width: '20px',
                        sortable: false,
                    },
                    {
                        data: 'title',
                        name: 'title',
                        className: 'coll-title',
                    },
                    {
                        data: 'is_closed',
                        name: 'is_closed',
                        className: 'coll-status',
                        width: '20px',
                    },
                    {
                        data: 'workers_count',
                        name: 'workers_count',
                        searchable: false,
                        width: '10px',
                        className: 'workers-count',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '10px',
                    },
                    {
                        data: null,
                        className: "actions",
                        render: function ( data, type, row, meta ) {
                            console.log(data);
                            
                            var delete_url = '{{ route("dashboard.hall.destroy", ":id") }}';
                            delete_url = delete_url.replace(':id', row.id);
                            
                            // var edit_url = '{{ route("dashboard.hall.edit", ":id") }}';
                            edit_url = editRoute.replace(':id', row.id);
                            
                            var show_url = '{{ route("dashboard.hall.show", ":id") }}';
                            show_url = show_url.replace(':id', row.id);
                            
                            
                            var toggle_closed = '{{ route("dashboard.hall.toggle_closed", ":id") }}';
                            toggle_closed = toggle_closed.replace(':id', row.id);
                            
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
                                    title="Stop suspension"
                                    data-toggle="tooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                                    </svg>
                                </a>
                            `;
                            // }
                            
                            return `
                            <form method="post" action="${delete_url}">
                                ${csrf}
                                ${methodDelete}
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
                            
                            <a href="#"
                                id="employeeSuspensionBtn"
                                class="float-right toggle-suspension-action action"
                                data-toggle="tooltip"
                                title="Employee suspension">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-circle-fill" viewBox="0 0 16 16">
                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5h-3z"/>
                                </svg>
                            </a>
                            
                            ${reinstate}
                            
                            <a href="${show_url}"
                                onclick="
                                   // event.preventDefault();
                                   // alert(333333);
                                   //openInfoModal(${row.id});
                                "
                                id="moreInfoBtn"
                                class="float-right show-action action"
                                data-toggle="tooltip"
                                title="Show hall details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                </svg>
                            </a>`;
                        },
                        sortable: false,
                        searchable: false,
                        width: '10px',
                    }
                ],
                "columnDefs": [
                    {
                        "targets": [4],
                        "render": function ( data, type, row, meta ) {
                            // console.log(row);
                            let date = new Date(data);
                            
                            let dateOfMonth = date.getDate();
                            dateOfMonth = dateOfMonth < 10 ? '0' + dateOfMonth.toString() : dateOfMonth;
                            
                            let month = date.getMonth();
                            month = month < 10 ? '0' + month.toString() : month;
                            
                            return dateOfMonth + '/' + month + '/' + date.getFullYear();
                        }
                    },
                    {
                        "targets": [2],
                        "render": function ( data, type, row, meta ) {                                
                            // return data;
                            if(!data)
                                return `
                                    <div class="status-circle status-circle-opened"></div>
                                `;
                            return `
                                <div class="status-circle status-circle-closed"></div>
                            `;
                        }
                    },
                    {
                        "targets": [3],
                        "render": function ( data, type, row, meta ) {                                
                            return `
                                <div>${data}</div>
                            `;
                        }
                    }
                ],
                // "ajax": "{{ route('dashboard.worker.data_list') }}",
                // 'createdRow': function (row, data, dataIndex) {
                //     $('td.coll-title', row).attr('data-toggle', "tooltip");
                //     $('td.coll-title', row).attr('data-placement', "top");
                //     $('td.coll-title', row).attr('title', "Hall`s title");
                //      // data-toggle="tooltip" data-placement="top" title="Hall`s title"
                //     // $(row).attr('data-code', data.Code);
                //     // $(row).attr('data-name', data.Name);
                // },
                fnDrawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                "ajax": {
                    "url": dataListRoute,
                    "type": "post",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // "data": {
                    //     // "user_id": 451
                    // }
                }
            });
            
            function openSuspendModal(hall_id){
                // _this.openSuspendModal(hall_id);
            }
        }
        
    }).init();
    
});