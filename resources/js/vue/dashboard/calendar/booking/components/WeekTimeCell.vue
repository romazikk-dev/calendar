<template>
    <div>
        {{ hours[index].hour }}.{{ hours[index].minute }}
    </div>
</template>

<script>
    export default {
        name: 'weekTimeCell',
        mounted() {
            
        },
        props: ['index'],
        data: function(){
            return {
                // range: helper.range.range,
                hours: [
                    {
                        hour: '00',
                        minute: '00',
                    },
                    {
                        hour: '01',
                        minute: '00',
                    },
                    {
                        hour: '02',
                        minute: '00',
                    },
                    {
                        hour: '03',
                        minute: '00',
                    },
                    {
                        hour: '04',
                        minute: '00',
                    },
                    {
                        hour: '05',
                        minute: '00',
                    },
                    {
                        hour: '06',
                        minute: '00',
                    },
                    {
                        hour: '07',
                        minute: '00',
                    },
                    {
                        hour: '08',
                        minute: '00',
                    },
                    {
                        hour: '09',
                        minute: '00',
                    },
                    {
                        hour: '10',
                        minute: '00',
                    },
                    {
                        hour: '11',
                        minute: '00',
                    },
                    {
                        hour: '12',
                        minute: '00',
                    },
                    {
                        hour: '13',
                        minute: '00',
                    },
                    {
                        hour: '14',
                        minute: '00',
                    },
                    {
                        hour: '15',
                        minute: '00',
                    },
                    {
                        hour: '16',
                        minute: '00',
                    },
                    {
                        hour: '17',
                        minute: '00',
                    },
                    {
                        hour: '18',
                        minute: '00',
                    },
                    {
                        hour: '19',
                        minute: '00',
                    },
                    {
                        hour: '20',
                        minute: '00',
                    },
                    {
                        hour: '21',
                        minute: '00',
                    },
                    {
                        hour: '22',
                        minute: '00',
                    },
                    {
                        hour: '23',
                        minute: '00',
                    },
                ],
            };
        },
        methods: {
            
        },
        components: {
            
        },
    }
</script>

<style scoped>
    
</style>