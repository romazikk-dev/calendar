<x-calendar-booking-layout>
    
    <x-slot name="scripts">
        <script type="text/javascript">
            
            // alert(111);
            
            var owner = @json($owner);
            var halls = @json($halls);
            var workers = @json($workers);
            var templates = @json($templates);
            
        </script>
        <script src="{{ asset('js/calendar-booking.js') }}?{{rand(100, 1000)}}"></script>
    </x-slot>
    
    <x-slot name="styles">
        <style>
            
            
        </style>
    </x-slot>
    
    {{$owner['id']}}
    
    <div id="calendarBooking"></div>
    
</x-dashboard-layout>