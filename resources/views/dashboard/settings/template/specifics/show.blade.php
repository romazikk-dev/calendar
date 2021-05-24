@extends('dashboard.settings.template.main')

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
        let showUrl = "{{ route('dashboard.settings.template.specifics') }}";
        let specifics = @json($specifics);
        let maxDeep = {{(int)$max_deep}};
        console.log(validationMessages);
        
    </script>
    
    <script type="text/javascript" src="{{ asset('js/dashboard/setting/specifics-index.js') }}?{{$rand}}"></script>
@endsection

@section('css')

    
    
@endsection

@section('content')
    
    <div id="specificsIndexApp"></div>
    
@endsection