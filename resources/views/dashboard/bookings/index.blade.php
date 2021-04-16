<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('client') }}
    </x-slot>
    
    <x-slot name="actions">
        
    </x-slot>
    
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
                        range: '{{ route("calendar_api.bookings.range", [$owner->id, ":start", ":end"] ) }}',
                        register: '{{ route("calendar_api.bookings.register") }}',
                        worker: {
                            index: '{{ route("calendar_api.bookings.worker.index") }}',
                        },
                        template: {
                            index: '{{ route("calendar_api.bookings.template.index") }}',
                        },
                        book: {
                            create: '{{ route("calendar_api.bookings.book.create", [$owner->id, ":hall_id", ":template_id", ":worker_id"]) }}',
                            cancel: '{{ route("calendar_api.bookings.book.cancel", [$owner->id, ":hall_id", ":template_id", ":worker_id", ":booking_id"]) }}',
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