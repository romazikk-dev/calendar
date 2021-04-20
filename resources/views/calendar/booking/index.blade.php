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
                        register: '{{ route("api.calendar.bookings.register", [$owner->id]) }}',
                        login: '{{ route("api.calendar.bookings.login", [$owner->id]) }}',
                        range: {
                            client: '{{ route("api.calendar.bookings.range.client", [$owner->id, ":start", ":end"] ) }}',
                            guest: '{{ route("api.calendar.bookings.range.guest", [$owner->id, ":start", ":end"] ) }}'
                        },
                        worker: {
                            index: '{{ route("api.calendar.bookings.worker.index", [$owner->id]) }}',
                        },
                        template: {
                            index: '{{ route("api.calendar.bookings.template.index", [$owner->id]) }}',
                        },
                        book: {
                            create: '{{ route("api.calendar.bookings.book.create", [$owner->id, ":hall_id", ":template_id", ":worker_id"]) }}',
                            cancel: '{{ route("api.calendar.bookings.book.cancel", [$owner->id, ":hall_id", ":template_id", ":worker_id", ":booking_id"]) }}',
                            all: '{{ route("api.calendar.bookings.book.all", [$owner->id, ":from_date"]) }}',
                        },
                        client: {
                            info: '{{ route("api.calendar.bookings.client.info", [$owner->id]) }}',
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