<template>
    <div>
        <div id="hallsAssignmentCard" class="card bg-light">
            <div class="card-header">
                <h5 class="float-left">Halls assignment:</h5>
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
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="usersAssignmentModal" tabindex="-1" aria-labelledby="usersAssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="usersAssignmentModalLabel">Select workers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li v-for="(worker,index) in workers">
                                <input :id="`worker_` + worker.id" type="checkbox" v-model="worker.selected">
                                <label :for="`worker_` + worker.id">{{worker.email}}</label>
                            </li>
                        </ul>
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
            setTimeout(() => {
    			this.setOldAssignHalls();
    		}, 300);
        },
        // props: ['postTitle'],
        data: function(){
            return {
                workers: null,
                selectedWorkers: [],
                oldAssignHalls: oldAssignHalls
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
                        $("#usersAssignmentModal").modal('show');
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else{
                    console.log(JSON.parse(JSON.stringify(this.workers)));
                    $("#usersAssignmentModal").modal('show');
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
                    $("#usersAssignmentModal").modal('hide');
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
            setOldAssignHalls: function(){
                if(typeof this.oldAssignWorker != 'undefined' && this.oldAssignWorker != null){
                    // console.log('setOldAssignWorker');
                    let oldAssignWorkerIds = Object.keys(this.oldAssignWorker);
                    if(this.workers == null){
                        axios.post(dataListUrl).then((response) => {
                            let workers = response.data.data;
                            for(let idx in workers){
                                if(oldAssignWorkerIds.includes(workers[idx].id.toString())){
                                    workers[idx].selected = true;
                                }else{
                                    workers[idx].selected = false;
                                }
                            }
                            this.workers = workers;
                            this.applySelect();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        for(let idx in this.workers){
                            if(oldAssignWorkerIds.includes(workers[idx].id)){
                                this.workers[idx].selected = true;
                            }else{
                                this.workers[idx].selected = false;
                            }
                        }
                        this.applySelect();
                    }
                }
            },
        },
        components: {
            
        },
    }
</script>

<style scoped>
    h5{
        position: relative;
        top: 5px;
    }
    #usersAssignmentModal ul{
        padding: 0px;
        margin: 0px;
    }
    #usersAssignmentModal ul li{
        padding: 0px;
        margin: 0px;
        list-style: none;
    }
    .assign-worker{
        display: none;
    }
    .card-body{
        padding: 0px;
    }
    .card-padding{
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    }
</style>