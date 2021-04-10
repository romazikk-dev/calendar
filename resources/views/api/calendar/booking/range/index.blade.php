<x-api-calendar-layout>
    
    <x-slot name="scripts">        
        
    </x-slot>
    
    <x-slot name="styles">
        <style>
            
        </style>
    </x-slot>
    
    <x-slot name="template_url">
        {{$template_url}}
    </x-slot>
    
    <x-slot name="description">
        Returns all dates in range and sets for every date booked time
    </x-slot>
    
    <x-slot name="response">
        <pre>@json($range, JSON_PRETTY_PRINT)</pre>
    </x-slot>
    
</x-api-calendar-layout>