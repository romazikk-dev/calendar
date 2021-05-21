@extends('dashboard.settings.template.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['client`s booking calendar', route('dashboard.settings.clients_booking_calendar.index')],
        ['Custom titles']
    ]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
    
        let csrfInput = `@csrf`;
        console.log(`@csrf`);
        
        let titles = @json($titles);
        console.log(titles);
        
        let formUrl = `{{ route('dashboard.settings.clients_booking_calendar.custom_titles') }}`;
        
        let requestField = @if(request()->has('field')) "{{ request()->get('field') }}" @else null @endif;
        let requestTab = @if(request()->has('tab')) "{{ request()->get('tab') }}" @else null @endif;
        
        console.log(111);
        console.log(requestTab);
        // console.log(tab);
        
    </script>
    
    <script type="text/javascript" src="{{ asset('js/dashboard/setting/custom-fields.js') }}?{{$rand}}"></script>

@endsection

@section('css')

@endsection

@section('content')
        
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <div id="customFieldsApp" data-picker-field-name="title"></div>
        
@endsection