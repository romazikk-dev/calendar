<template>
    <div>
        
        <div>
            <button @click="openModal()" type="button" class="btn btn-primary btn-sm open-select-worker-modal">
                Select
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                </svg>
            </button>
            
            <div v-for="(worker,index) in selectedWorkers" class="alert alert-primary" role="alert">
                <b>{{worker.first_name}} {{worker.last_name}}</b><br>
                <span class="small">{{worker.email}}</span>
                <input class="assign-worker" :name="`assign_worker[` + worker.id + `]`" type="checkbox" checked>
                <button @click="dismissSelected(worker)" type="button" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <!-- <div id="usersAssignment" class="card bg-light">
            <div class="card-header">
                <h5 class="float-left">Assign workers:</h5>
                <button @click="openModal()" type="button" class="btn btn-primary float-right">Select</button>
            </div>
            <div class="card-body" :class="{'card-padding': (selectedWorkers.length > 0 ? true : false)}">
                <div v-for="(worker,index) in selectedWorkers" class="alert alert-primary" role="alert">
                    {{worker.email}}
                    <input class="assign-worker" :name="`assign_worker[` + worker.id + `]`" type="checkbox" checked>
                    <button @click="dismissSelected(worker)" type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div> -->
        
        <!-- Modal -->
        <div class="modal fade modal-custom-dark-header-footer" id="workerAssignmentModal" tabindex="-1" aria-labelledby="workerAssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="workerAssignmentModalLabel">Select workers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <table class="all-workers-list">
                            <tbody>
                                <tr v-for="(worker,index) in workers">
                                    <td>
                                        <!-- <input class="custom-control-input" :id="`worker_` + worker.id" type="checkbox" v-model="worker.selected"> -->
                                        <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                            <input class="custom-control-input" :id="`worker_` + worker.id" type="checkbox" v-model="worker.selected">
                                            <label class="custom-control-label" :for="`worker_` + worker.id"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <label :for="`worker_` + worker.id">
                                            <b>{{worker.first_name}} {{worker.last_name}}</b><br>
                                            <span class="small">{{worker.email}}</span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- <ul>
                            <li v-for="(worker,index) in workers">
                                <input :id="`worker_` + worker.id" type="checkbox" v-model="worker.selected">
                                <label :for="`worker_` + worker.id">
                                    {{worker.first_name}} {{worker.last_name}}<br>
                                    {{worker.email}}
                                </label>
                            </li>
                        </ul> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="applySelect()" type="button" class="btn btn-primary">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component mounted.');
            
            // $(function () {
            //     setTimeout(function(){
            //         $('.bs-timepicker').timepicker();
            //     }, 300);
            //     // alert(111);
            // });
            
            // this.greet();
            // console.log(111);
            // console.log(this.my);
            // setTimeout(() => {
                // this.setWeekdays();
            // }, 300);
            // this.setOldAssignWorker();
            setTimeout(() => {
    			this.setOldAssignWorker();
    		}, 300);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                workers: null,
                selectedWorkers: [],
                oldAssignWorker: oldAssignWorker
            };
        },
        methods: {
            openModal: function(){
                // e.preventDefault();
                if(this.workers == null){
                    axios.post(dataListUrl).then((response) => {
                        let workers = response.data.data;
                        for(let idx in workers){
                            workers[idx].selected = false;
                        }
                        this.workers = workers;
                        console.log(JSON.parse(JSON.stringify(this.workers)));
                        $("#workerAssignmentModal").modal('show');
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else{
                    console.log(JSON.parse(JSON.stringify(this.workers)));
                    $("#workerAssignmentModal").modal('show');
                }
            },
            applySelect: function(){
                // e.preventDefault();
                if(this.workers != null){
                    let selectedWorkers = [];
                    for(let idx in this.workers){
                        if(this.workers[idx].selected)
                            selectedWorkers.push(this.workers[idx]);
                    }
                    this.selectedWorkers = JSON.parse(JSON.stringify(selectedWorkers));
                    $("#workerAssignmentModal").modal('hide');
                }
            },
            dismissSelected: function(worker){
                // e.preventDefault();
                if(this.workers != null){
                    for(let idx in this.workers){
                        if(this.workers[idx].selected && this.workers[idx].id == worker.id)
                            this.workers[idx].selected = false;
                    }
                    this.applySelect();
                }
            },
            setOldAssignWorker: function(){
                
                if(typeof this.oldAssignWorker != 'undefined' && this.oldAssignWorker != null){
                    console.log('setOldAssignWorker');
                    let oldAssignWorkerIds = Object.keys(this.oldAssignWorker);
                    if(this.workers == null){
                        axios.post(dataListUrl).then((response) => {
                            let workers = response.data.data;
                            for(let idx in workers){
                                // console.log(oldAssignWorkerIds, workers[idx].id);
                                if(oldAssignWorkerIds.includes(workers[idx].id.toString())){
                                    workers[idx].selected = true;
                                }else{
                                    workers[idx].selected = false;
                                }
                            }
                            // this.workers = JSON.parse(JSON.stringify(workers));
                            this.workers = workers;
                            // console.log(this.workers);
                            // console.log(this.workers[0].selected);
                            this.applySelect();
                            // console.log(JSON.parse(JSON.stringify(this.workers)));
                            // $("#workerAssignmentModal").modal('show');
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        // console.log(JSON.parse(JSON.stringify(this.workers)));
                        // $("#workerAssignmentModal").modal('show');
                        for(let idx in this.workers){
                            if(oldAssignWorkerIds.includes(workers[idx].id)){
                                this.workers[idx].selected = true;
                            }else{
                                this.workers[idx].selected = false;
                            }
                        }
                        this.applySelect();
                    }
                    // console.log(this.workers);
                    // this.applySelect();
                }
                
                // function setOldAssignWorker(oldAssignWorkerIds){
                // 
                // }
                // console.log(Object.keys(this.oldAssignWorker));
                // e.preventDefault();
                // if(this.workers != null){
                //     for(let idx in this.workers){
                //         if(this.workers[idx].selected && this.workers[idx].id == worker.id)
                //             this.workers[idx].selected = false;
                //     }
                //     this.applySelect();
                // }
            },
        },
        components: {
            
        },
    }
</script>

<style lang="scss" scoped>
    h5{
        position: relative;
        top: 5px;
    }
    #workerAssignmentModal ul{
        padding: 0px;
        margin: 0px;
    }
    #workerAssignmentModal ul li{
        padding: 0px;
        margin: 0px;
        list-style: none;
    }
    .open-select-worker-modal{
        svg{
            position: relative;
            top: -2px;
        }
    }
    .assign-worker{
        display: none;
    }
    .alert{
        margin-bottom: 0px;
        margin-top: 10px;
        line-height: 1em;
        span{
            &.small{
                line-height: 1em;
            }
        }
        .close{
            position: absolute;
            top: 5px;
            right: 10px;
        }
    }
    table.all-workers-list{
        width: 100%;
        tr{
            td{
                vertical-align: top;
                line-height: 1em;
                border-top: 1px solid #dee2e6;
                label{
                    cursor: pointer;
                }
                &:first-child{
                    width: 40px;
                    padding-left: 14px;
                }
                &:last-child{
                    padding-top: 7px;
                    label{
                        display: block;
                        width: 100%;
                    }
                }
            }
            &:nth-child(odd){
                td{
                    background-color: rgba(0,0,0,.05);
                }
            }
            &:last-child{
                td{
                    border-bottom: 1px solid #dee2e6;
                }
            }
        }
    }
    /* .card-body{
        padding: 0px;
    }
    .card-padding{
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    } */
</style>