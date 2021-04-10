<x-calendar-booking-layout>
    
    <x-slot name="scripts">
        <script type="text/javascript">
            
            // alert(111);
            
            var owner = @json($owner);
            var halls = @json($halls);
            var workers = @json($workers);
            var templates = @json($templates);
            var filters = @json($filters);
            
            var routes = {
                calendar: {
                    booking: {
                        range: '{{ route("calendar_api.bookings.index", [$owner->id, ":start", ":end"] ) }}',
                        register: '{{ route("calendar_api.bookings.register") }}',
                        worker: {
                            index: '{{ route("calendar_api.bookings.worker.index") }}',
                        },
                        template: {
                            index: '{{ route("calendar_api.bookings.template.index") }}',
                        }
                    }
                }
            }
            
            // console.log(routes.calendar.booking.register);
            
        </script>
        <script src="{{ asset('js/calendar-booking.js') }}?{{rand(100, 1000)}}"></script>
    </x-slot>
    
    <x-slot name="styles">
        <style>
            
            
        </style>
    </x-slot>
    
    <div id="calendarBooking" data-user-id="{{$owner['id']}}"></div>
    
</x-dashboard-layout>