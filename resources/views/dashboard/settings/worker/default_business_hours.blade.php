@extends('dashboard.settings.template.main')

@section('scripts')

    <script type="text/javascript">
        
        @if(old('business_hours'))
            var businessHours = @json(old('business_hours'));
        @elseif(!empty($business_hours))
            var businessHours = @json($business_hours);
        @endif
        
    </script>
    <script type="text/javascript" src="{{ asset('js/dashboard/halls/hall-business-hours.js') }}?{{$rand}}"></script>

@endsection

@section('css')

    
    
@endsection

@section('content')
    
    <div id="hallBusinessHours"></div>
    
@endsection