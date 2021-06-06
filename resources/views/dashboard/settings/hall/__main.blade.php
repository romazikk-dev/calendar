@extends('dashboard.settings.template.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [['hall', route('dashboard.settings.hall.index')], ['main']]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
    
        let validationMessages = @json($validation_messages);
        
    </script>
    
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('js/dashboard/holidays.js') }}?{{$rand}}"></script> -->

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
        <div id="holidaysApp" data-show-empty-placeholder="true"></div>
    </form>
    
@endsection