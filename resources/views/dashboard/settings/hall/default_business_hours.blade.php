@extends('dashboard.settings.template.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [['hall', route('dashboard.settings.hall.index')], ['hall`s default bussiness hours']]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
        
        @if(old('business_hours'))
            var businessHours = @json(old('business_hours'));
            // console.log(JSON.parse(JSON.stringify(businessHours)));
        @elseif(!empty($business_hours))
            var businessHours = @json($business_hours);
        @endif
        
    </script>
    <script type="text/javascript" src="{{ asset('js/dashboard/halls/hall-business-hours.js') }}?{{$rand}}"></script>

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
        <div id="hallBusinessHours"></div>
    </form>
    
@endsection