<template>
    <div id="businessHours" class="card bg-light">
        <div class="card-header">
            <h5 class="float-left">Phones:</h5>
            <button click="" type="button" class="btn btn-sm btn-primary float-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </button>
        </div>
        <div class="card-body">
            
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            
        },
        // props: ['postTitle'],
        data: function(){
            return {
                phones: [
                    {
                        id: 1,
                        phone: '+380957853403',
                        type: 'home'
                    }
                ]
            };
        },
        methods: {
            setWeekdays: function(){
                
            },
        },
        components: {
            
        },
    }
</script>

<style>
    
</style>