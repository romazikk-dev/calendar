@extends('dashboard.settings.template.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['client`s booking calendar', route('dashboard.settings.clients_booking_calendar.index')],
        ['languages']
    ]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
        
        // @if(old('business_hours'))
        //     var businessHours = @json(old('business_hours'));
        //     // console.log(JSON.parse(JSON.stringify(businessHours)));
        // @elseif(!empty($business_hours))
        //     var businessHours = @json($business_hours);
        // @endif
        
    </script>

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
        <div class="languages-page mt-3">
            <div class="row">
                @foreach($languages as $lang)
                <div class="coll col col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    <label class="custom-checkbox">
                        {{$lang['title']}}
                        <input name="lang[{{$lang['abr']}}]" type="checkbox" @if($lang['on'] == 1) checked="checked" @endif>
                        <span class="checkmark"></span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </form>
    
@endsection