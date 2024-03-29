<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('booking') }}
    </x-slot>
    
    <x-slot name="actions">
        
    </x-slot>
    
    <x-slot name="scripts">
        <script src="{{asset('/dists/mc-calendar/mc-calendar.min.js?11')}}"></script>
        <script type="text/javascript">
            
            // alert(111);
            
            
            // console.log(73737373);
            // console.log(token);
            var timezone = '{{$timezone}}';
            var view = @json($view);
            var durationRange = @json($duration_range);
            
            var calendarSettings = @json($calendar_settings);
            var owner = @json($owner);
            var halls = @json($halls);
            var templateSpecifics = @json($template_specifics);
            var templateSpecificsAsIdKey = @json($template_specifics_as_id_key);
            
            var mainSettings = @json($main_settings);
            
            // console.log(templateSpecificsAsIdKey);
            // console.log(33333333);
            // console.log(halls);
            
            // var workers = @@json($workers);
            // var templates = @@json($templates);
            var filters = @json($filters);
            var movingEvent = @json($moving_event);
            var newEvent = @json($new_event);
            var freeGetDataParams = @json($free_get_data_params);
            // console.log(333333333);
            // console.log(filters);
            
            var routes = {
                calendar: {
                    booking: {
                        booking: {
                            all: '{{ route("dashboard.ajax.booking.get", [":start", ":end"]) }}',
                            free: '{{ route("dashboard.ajax.booking.free", [":start", ":end"]) }}',
                            edit: '{{ route("dashboard.ajax.booking.edit", [":id"]) }}',
                            create: '{{ route("dashboard.ajax.booking.create", [":client", ":hall", ":template", ":worker"]) }}',
                            approve: '{{ route("dashboard.ajax.booking.approve", [":id"]) }}',
                            delete: '{{ route("dashboard.ajax.booking.delete", [":id"]) }}',
                        },
                        worker: {
                            get: '{{ route("dashboard.ajax.worker.get") }}',
                        },
                        template: {
                            get: '{{ route("dashboard.ajax.template.get") }}'
                        },
                        client: {
                            get: '{{ route("dashboard.ajax.client.get") }}',
                        }
                    }
                }
            }
            
            // console.log(routes.calendar.booking.register);
            
        </script>
        <!-- <script src="{{ asset('js/calendar-booking.js') }}?{{rand(100, 1000)}}"></script> -->
        <script src="{{ asset('js/dashboard/ts/calendar-helper.js') }}?{{rand(100, 1000000000)}}"></script>
        
        <script src="{{ asset('js/dashboard/calendar-booking-admin-helper.js') }}?{{rand(100, 1000000000)}}"></script>
        <script src="{{ asset('js/dashboard/calendar-booking-admin.js') }}?{{rand(100, 1000000000)}}"></script>
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="{{asset('/dists/mc-calendar/mc-calendar.min.css?11')}}">
        <style>
            html{
                min-height: 100%;
            }
            body{
                min-height: 100%;
            }
            
        </style>
    </x-slot>
    
    <div class="calendar-all-bookings">
        <div id="calendarBooking" data-user-id="{{$owner['id']}}"></div>
    </div>
    
</x-dashboard-layout>