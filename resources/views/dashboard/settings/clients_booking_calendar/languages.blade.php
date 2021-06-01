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
        
        // $(document).ready( function() {
        //     $("input[type=checkbox]").change(function(){
        //         let lang = $(this).data('lang');
        //         if($(this).is(':checked')){
        //             $('.position-' + lang).removeAttr('disabled').removeClass('pos-disabled');
        //             // alert(lang);
        //         }else{
        // 
        //             $('.position-' + lang).attr('disabled','disabled').addClass('pos-disabled');
        //         }
        //             // alert(11);
        //     });
        // });
        
    </script>

@endsection

@section('css')
    <style>
        .pos-disabled{
            opacity: 0.5!important;
            /* display: none; */
        }
    </style>
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
                        <input data-lang="{{$lang['abr']}}"
                            class="checkbox-{{$lang['abr']}}"
                            name="lang[{{$lang['abr']}}]"
                            type="checkbox" @if($lang['on'] == 1) checked="checked" @endif>
                        <span class="checkmark"></span>
                    </label>
                    <div class="form-group">
                        <label class="small text-capitalize" for="position_{{$lang['abr']}}">Position</label>
                        <input name="position[{{$lang['abr']}}]"
                            type="number"
                            class="form-control position-{{$lang['abr']}}"
                            id="position_{{$lang['abr']}}"
                            value="{{$lang['position']}}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </form>
    
@endsection