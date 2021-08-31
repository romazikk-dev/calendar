<template>
    <div>
        
        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>
                        Title
                    </th>
                    <th data-toggle="tooltip" data-placement="auto" title="Price">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                        </svg>
                    </th>
                    <th data-toggle="tooltip" data-placement="auto" title="Duration">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                            <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                        </svg>
                    </th>
                    <th data-toggle="tooltip" data-placement="auto" title="Assigned to employees">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-people-fill">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"></path>
                            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"></path>
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
            
            // alert(111);
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
            regActionsOnModalClose: function(){
                $("#modal").on('hidden.bs.modal', () => {
                    this.infoModalData = null;
                    this.suspendModalData = null;
                    this.modalDataUrl = null;
                    this.showContent = null;
                });
            },
            updateData: function(){
                $('#dataTable').DataTable().ajax.reload();
                this.updateSuspendModalData();
            },
            openModal: function(type){
                if(type == 'info'){
                    let url = showRoute.replace(':id', id);
                    axios.get()
                }
            },
            regClickBtns: function(){
                this.regClickMoreInfoBtn();
                // this.regClickToggleSuspensionActionBtn();
            },
            updateSuspendModalData: function(){
                axios.get(this.modalDataUrl)
                .then((response) => {
                    this.suspendModalData = response.data;
                }).catch((error) => {}).then(() => {});
            },
            regClickMoreInfoBtn: function(){
                $('.show-action').off('click');
                $('.show-action').on('click', (e) => {
                    e.preventDefault();
                    
                    // this.resetAllModalsData();
                    let id = parseInt($(e.target).closest('tr').attr('id'));
                    let url = showRoute.replace(':id', id);
                    url += '?with_workers=1';
                    axios.get(url)
                    .then((response) => {
                        this.infoModalData = response.data;
                        // this.showContent = 'info';
                        this.showModal('info');
                        console.log(this.infoModalData);
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
            isSuspended: function(from, to){
                if(from == null || from == null)
                    return true;
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(from);
                let toMoment = moment(to);
                return (currentDateMoment.diff(fromMoment) >= 0 && currentDateMoment.diff(toMoment) <= 0);
                // console.log(currentDateMoment.format('YYYY-MM-DD HH-mm-ss'));
                // console.log(fromMoment.format('YYYY-MM-DD HH-mm-ss'));
                // console.log(toMoment.format('YYYY-MM-DD HH-mm-ss'));
                // return false;
            },
            isSuspentionInFuture: function(from, to){
                if(from == null || from == null)
                    return false;
                let currentDateMoment = moment(this.currentDate);
                let fromMoment = moment(from);
                // let toMoment = moment(to);
                return (currentDateMoment.diff(fromMoment) < 0);
            },
            initDataTable: function(){
                let _this = this;
                $('#dataTable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [[ 5, "desc" ]],
                    // "order": [],
                    "createdRow": function(row, data, dataIndex){
                        $(row).attr('id', data.id);
                        // $(row).attr('is-closed', data.is_closed);
                        $(row).attr('workers-count', data.workers_count);
                        $(row).attr('created-at', data.created_at);
                        if(data.suspension != null){
                            if(_this.isSuspended(data.suspension.from, data.suspension.to)){
                                $(row).addClass('table-danger');
                            }else if(_this.isSuspentionInFuture(data.suspension.from, data.suspension.to)){
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
                            // data: null,
                            // className: 'coll-status',
                            // width: '20px',
                            data: 'price',
                            name: 'price',
                            className: 'coll-price',
                            width: '20px',
                        },
                        {
                            // data: null,
                            // className: 'coll-status',
                            // width: '20px',
                            data: 'duration',
                            name: 'duration',
                            className: 'coll-duration',
                            width: '20px',
                            searchable: false,
                        },
                        {
                            // data: 'workers_count',
                            data: null,
                            name: 'workers_count',
                            searchable: false,
                            width: '20px',
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
                                
                                var delete_url = deleteRoute.replace(':id', row.id);
                                var edit_url = editRoute.replace(':id', row.id);
                                var show_url = showRoute.replace(':id', row.id);
                                
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
                                
                                <a href="${show_url}"
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
                            "targets": [1],
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                // $(td).addClass('dadsadadsa');
                            },
                            "render": function ( data, type, row, meta ) {
                                let specific_titled_trace = row.specific_titled_trace.map((item) => item.toLowerCase());
                                return data + '<div class="small">' + specific_titled_trace.join(`
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                                    </svg>
                                `) + '</div>';
                            }
                        },
                        {
                            "targets": [5],
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-toggle', 'tooltip');
                                $(td).attr('data-placement', 'auto');
                                $(td).attr('data-original-title', 'Created at');
                            },
                            "render": function ( data, type, row, meta ) {
                                return helper.formatCreatedDate(data);
                            }
                        },
                        {
                            "targets": [2],
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-toggle', 'tooltip');
                                $(td).attr('data-placement', 'auto');
                                if(rowData.price != null){
                                    $(td).attr('data-original-title', 'Price');
                                }else{
                                    $(td).attr('data-original-title', 'No price');
                                }
                            },
                            "render": function ( data, type, row, meta ) {
                                // return row.price != null ? row.price : '---';
                                if(row.price != null){
                                    return row.price;
                                }else{
                                    return `
                                        <span class="notice-badge notice-badge-warning text-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                            </svg>
                                        </span>
                                    `;
                                }
                            }
                        },
                        {
                            "targets": [3],
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td).attr('data-toggle', 'tooltip');
                                $(td).attr('data-placement', 'auto');
                                $(td).attr('data-original-title', 'Duration');
                            },
                            "render": function ( data, type, row, meta ) {                                
                                return `
                                    <div>${data}</div>
                                `;
                            }
                        },
                        {
                            "targets": [4],
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                let workersCount = typeof rowData.workers == 'undefined' ? 0 : rowData.workers.length;
                                $(td).attr('data-toggle', 'tooltip');
                                $(td).attr('data-placement', 'auto');
                                if(workersCount > 0){
                                    $(td).attr('data-original-title', 'Assigned to employees');
                                }else{
                                    $(td).attr('data-original-title', 'Do not assigned to any employee');
                                }
                            },
                            "render": function ( data, type, row, meta ) {
                                let workersCount = typeof data.workers == 'undefined' ? 0 : data.workers.length;
                                if(workersCount > 0){
                                    return `
                                        <div>${workersCount}</div>
                                    `;
                                }else{
                                    return `
                                        <span class="notice-badge notice-badge-warning text-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                            </svg>
                                        </span>
                                    `;
                                }
                            }
                        }
                    ],
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