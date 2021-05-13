<template>
    <div>
        
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
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                
                <modal-info-content :data="infoModalData" v-if="showContent == 'info'"></modal-info-content>
                <modal-suspension-content :data="suspendModalData"
                    @changed="updateData"
                    v-if="showContent == 'suspension'"></modal-suspension-content>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    import ModalInfoContent from "./ModalInfoContent.vue";
    import ModalSuspensionContent from "./ModalSuspensionContent.vue";
    export default {
        name: 'app',
        mounted() {
            this.regActionsOnModalClose();
            this.initDataTable();
            // this.regClickMoreInfoBtn();
        },
        // props: ['postTitle'],
        data: function(){
            return {
                infoModalData: null,
                suspendModalData: null,
                modalDataUrl: null,
                showContent: null,
                currentDate: new Date(),
            };
        },
        methods: {
            isStatus: function(type, suspension){
                return helper.isStatus(type, (typeof suspension == 'undefined' || suspension == null ? null : suspension));
            },
            regActionsOnModalClose: function(){
                $("#modal").on('hidden.bs.modal', () => {
                    this.infoModalData = null;
                    this.suspendModalData = null;
                    this.modalDataUrl = null;
                    this.showContent = null;
                });
            },
            updateData: function(){
                // alert(123123);
                // this.initDataTable();
                // $('#dataTable').data.reload();
                $('#dataTable').DataTable().ajax.reload();
                this.updateSuspendModalData();
            },
            // resetAllModalsData: function(){
            //     this.infoModalData = null;
            //     this.suspendModalData = null;
            //     this.showContent = null;
            // },
            openModal: function(type){
                if(type == 'info'){
                    let url = showRoute.replace(':id', id);
                    axios.get()
                }
            },
            regClickBtns: function(){
                this.regClickReinstateBtn();
                this.regClickMoreInfoBtn();
                this.regClickToggleSuspensionActionBtn();
            },
            updateSuspendModalData: function(){
                axios.get(this.modalDataUrl)
                .then((response) => {
                    this.suspendModalData = response.data;
                }).catch((error) => {}).then(() => {});
            },
            regClickReinstateBtn: function(){
                $('.stop-suspension-action').off('click');
                $('.stop-suspension-action').on('click', (e) => {
                    e.preventDefault();
                    let tr = $(e.target).closest('tr');
                    let id = tr.attr('id');
                    let url = toggleSuspension.replace(':id', id);
                    
                    axios.post(url, {
                        type: 'disable'
                    }).then((response) => {
                        // this.$emit('changed');
                        this.updateData();
                    }).catch((error) => {
                    
                    }).then(() => {
                    
                    });
                });
            },
            regClickToggleSuspensionActionBtn: function(){
                $('.toggle-suspension-action').off('click');
                $('.toggle-suspension-action').on('click', (e) => {
                    e.preventDefault();
                    // this.resetAllModalsData();
                    
                    let id = parseInt($(e.target).closest('tr').attr('id'));
                    let url = showRoute.replace(':id', id);
                    url += '?with_suspension=1';
                    axios.get(url)
                    .then((response) => {
                        this.modalDataUrl = url;
                        this.suspendModalData = response.data;
                        // this.showContent = 'info';
                        this.showModal('suspension');
                        // console.log(this.suspendModalData);
                    }).catch((error) => {
                        
                    }).then(() => {
                        
                    });
                });
            },
            regClickMoreInfoBtn: function(){
                $('.show-action').off('click');
                $('.show-action').on('click', (e) => {
                    e.preventDefault();
                    // this.resetAllModalsData();
                    let id = parseInt($(e.target).closest('tr').attr('id'));
                    let url = showRoute.replace(':id', id);
                    url += '?with_phones=1&with_workers=1&with_suspension=1';
                    axios.get(url)
                    .then((response) => {
                        this.infoModalData = response.data;
                        // this.showContent = 'info';
                        this.showModal('info');
                        // console.log(this.infoModalData);
                    }).catch((error) => {
                        
                    }).then(() => {
                        
                    });
                    // this.openModal('info', e);
                    // $('#interactionModal').modal('show');
                });
            },
            showModal: function(showContent){
                this.showContent = showContent;
                $('#modal').modal('show');
            },
            isSuspended: function(suspension){
                return this.isStatus('suspended', suspension);
            },
            isSuspentionInFuture: function(suspension){
                return this.isStatus('future_suspension', suspension);
            },
            initDataTable: function(){
                let _this = this;
                $('#dataTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    // "order": [[ 1, "asc" ]],
                    "order": [],
                    "createdRow": function(row, data, dataIndex){
                        $(row).attr('id', data.id);
                        // $(row).attr('is-closed', data.is_closed);
                        $(row).attr('workers-count', data.workers_count);
                        $(row).attr('created-at', data.created_at);
                        if(data.suspension != null){
                            if(_this.isSuspended(data.suspension)){
                                $(row).addClass('table-danger');
                            }else if(_this.isSuspentionInFuture(data.suspension)){
                                $(row).addClass('table-warning');
                            }
                        }
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
                            data: null,
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
                                // console.log(data);
                                
                                // var delete_url = '{{ route("dashboard.hall.destroy", ":id") }}';
                                var delete_url = deleteRoute.replace(':id', row.id);
                                
                                // var edit_url = '{{ route("dashboard.hall.edit", ":id") }}';
                                var edit_url = editRoute.replace(':id', row.id);
                                
                                // var show_url = '{{ route("dashboard.hall.show", ":id") }}';
                                var show_url = showRoute.replace(':id', row.id);
                                
                                
                                // var toggle_closed = '{{ route("dashboard.hall.toggle_closed", ":id") }}';
                                var toggle_closed = toggleSuspension.replace(':id', row.id);
                                
                                let stopSuspensionVisibility = data.worker_suspension_id !== null ? '' : 'd-none';
                                
                                let reinstate = '';
                                if(row.suspension != null &&
                                    (_this.isSuspended(row.suspension) ||
                                    _this.isSuspentionInFuture(row.suspension))){
                                        
                                    let isSuspentionInFuture = _this.isSuspentionInFuture(row.suspension);
                                    let reinstateAttrTitle = isSuspentionInFuture ?
                                        `
                                            Undo future<br>
                                            employee suspension<br>
                                            from <b>` + _this.formatDataDateForDateChooser(row.suspension.from) + `</b><br>
                                            to <b>` + _this.formatDataDateForDateChooser(row.suspension.to) + `</b>
                                        ` : `Reinstate worker`;
                                    let reinstateDropQuestion = isSuspentionInFuture ?
                                        `
                                            Do you want undo future suspension for this employee?
                                        ` :
                                        `
                                            Do you want reinstate an employee?
                                        `;
                                    let reinstateIcon = isSuspentionInFuture ?
                                        `
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                            </svg>
                                        ` :
                                        `
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                                            </svg>
                                        `;
                                    
                                    reinstate = `
                                        <div class="action-drop dropup float-right">
                                            <a href="#"
                                                id="dropdownReinstateButton_${row.id}"
                                                class="action text-info data-tooltip"
                                                data-toggle="dropdown"
                                                data-placement="bottom"
                                                title="${reinstateAttrTitle}"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                                    ${reinstateIcon}
                                            </a>
                                            <div onclick="event.stopPropagation()" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownReinstateButton_${row.id}">
                                                ${reinstateDropQuestion}
                                                <div class="btnns">
                                                    
                                                    <a href="#" class="btnn text-primary stop-suspension-action">
                                                            Yes
                                                    </a>
                                                    
                                                    <a href="#"
                                                        onclick="
                                                            event.preventDefault();
                                                            $('#dropdownReinstateButton_${row.id}').click();
                                                        " class="btnn text-primary">
                                                            No
                                                    </a>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }
                                
                                return `
                                <div class="action-drop dropup float-right">
                                    <a href="#"
                                        id="dropdownDeleteButton_${row.id}"
                                        class="action text-info data-tooltip"
                                        data-toggle="dropdown"
                                        data-placement="bottom"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                    </a>
                                    <div onclick="event.stopPropagation()" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownDeleteButton_${row.id}">
                                        Do you want delete this hall?
                                        <div class="btnns">
                                            <form method="post" action="${delete_url}">
                                                ${csrf}
                                                ${methodDelete}
                                                <a href="${delete_url}"
                                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                                    class="btnn text-primary">
                                                        Yes
                                                </a>
                                                <a href="${delete_url}"
                                                    onclick="event.preventDefault(); $('#dropdownDeleteButton_${row.id}').click();"
                                                    class="btnn text-primary"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false">
                                                        No
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="${edit_url}"
                                    class="float-right edit-action action text-info"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </a>
                                
                                <a href="#"
                                    id="employeeSuspensionBtn"
                                    class="float-right toggle-suspension-action action text-info"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Suspension">
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
                                    class="float-right show-action action text-info"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Details">
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
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-toggle', 'tooltip');
                                $(td).attr('data-placement', 'auto');
                                
                                $(td).attr(
                                    'title',
                                    helper.getStatusTooltipTitle(
                                        typeof rowData.suspension == 'undefined' || rowData.suspension == null ?
                                        null : rowData.suspension
                                    )
                                );
                            },
                            "render": function ( data, type, row, meta ) {
                                if(row.suspension != null){
                                    if(_this.isSuspended(row.suspension)){
                                        // $(row).addClass('table-danger');
                                        return `
                                            <div class="status-circle bg-danger"></div>
                                        `;
                                    }else if(_this.isSuspentionInFuture(row.suspension)){
                                        // $(row).addClass('table-warning');
                                        return `
                                            <div class="status-circle bg-warning"></div>
                                        `;
                                    }
                                }
                                return `
                                    <div class="status-circle bg-success"></div>
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
                        $('[data-toggle="tooltip"]').tooltip({html: true});
                        $('.data-tooltip').tooltip({html: true});
                        
                        _this.regClickBtns();
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
            },
        },
        components: {
            ModalInfoContent,
            ModalSuspensionContent
        },
    }
</script>

<style scoped>
    
</style>