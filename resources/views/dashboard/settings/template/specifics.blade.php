@extends('dashboard.settings.view.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['template', route('dashboard.settings.template.index')],
        ['Specifics of template']
    ]) }}
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script type="text/javascript">
        
        let validationMessages = @json($validation_messages);
        console.log(validationMessages);
        
    </script>
    
    <script type="text/javascript" src="{{ asset('js/dashboard/specifics.js') }}?{{$rand}}"></script>
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
    
    <form id="specificForm" action="" method="post">
        @csrf
        <div id="specificsApp" data-form-id="specificForm"></div>
    </form>
    
@endsection