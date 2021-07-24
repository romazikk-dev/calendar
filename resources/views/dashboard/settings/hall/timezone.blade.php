@extends('dashboard.settings.view.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [['hall', route('dashboard.settings.hall.index')], ['default timezone']]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
    
        let validationMessages = @json($validation_messages);
        let timezones = @json($timezones);
        let timezone_values = @json($timezone_values);
        
        @if($errors->any())
            // let errors = {}
            let errors = @json($errors->getMessages());
            console.log(JSON.parse(JSON.stringify(errors)));
        @endif
        
        // console.log(JSON.parse(JSON.stringify(errors)));
        
        // console.log(timezones);
        
    </script>
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('js/dashboard/timezone-picker.js') }}?{{$rand}}"></script>

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

    <form action="" method="post">
        @csrf
        <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
        <div class="clearfix"></div>
        <div class="timezone-picker-page">
            <div id="timezonePickerApp"></div>
        </div>
    </form>
    
@endsection