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
    
    <div id="customFieldsApp"></div>
        
@endsection