<x-calendar-booking-layout>
    
    <x-slot name="scripts">
        <script type="text/javascript">
            
            // alert(111);
            
            var token = @if(!empty($token)) '{{$token}}' @else null @endif;
            
            // console.log(73737373);
            // console.log(token);
            
            var owner = @json($owner);
            var halls = @json($halls);
            var templateSpecifics = @json($template_specifics);
            var templateSpecificsAsIdKey = @json($template_specifics_as_id_key);
            
            var customTitles = @json($custom_titles);
            
            // console.log(templateSpecificsAsIdKey);
            // console.log(33333333);
            // console.log(halls);
            
            // var workers = @@json($workers);
            // var templates = @@json($templates);
            var filters = @json($filters);
            // console.log(333333333);
            // console.log(filters);
            
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
                            // cancel: '{{ route("api.calendar.bookings.book.cancel", [$owner->id, ":hall_id", ":template_id", ":worker_id", ":booking_id"]) }}',
                            cancel: '{{ route("api.calendar.bookings.book.cancel", [$owner->id, ":booking_id"]) }}',
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
        <!-- <script src="{{ asset('js/calendar-booking.js') }}?{{rand(100, 1000)}}"></script> -->
        <script src="{{ asset('js/calendar-booking-helper-2.js') }}?{{rand(100, 1000000000)}}"></script>
        <script src="{{ asset('js/calendar-booking-2.js') }}?{{rand(100, 1000000000)}}"></script>
    </x-slot>
    
    <x-slot name="styles">
        <style>
            html{
                min-height: 100%;
            }
            body{
                min-height: 100%;
            }
            
        </style>
    </x-slot>
    
    <div id="calendarBooking" data-user-id="{{$owner['id']}}"></div>
    
</x-dashboard-layout>