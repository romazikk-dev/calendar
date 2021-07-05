@extends('dashboard.settings.template.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['admin`s booking calendar', route('dashboard.settings.admins_booking_calendar.index')],
        ['Main']
    ]) }}
@endsection

@section('scripts')

    <script type="text/javascript">
    
        let csrfInput = `@csrf`;
        // console.log(`@csrf`);
        
    </script>
    
    <!-- <script type="text/javascript" src="{{ asset('js/dashboard/setting/custom-fields.js') }}?{{$rand}}"></script> -->

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
        <div>
            <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
        </div>
        
        <div class="clearfix"></div>
        @if(!empty($setting))
        <div class="clients-booking-calendar-main-setting">
            <div class="row">
                
                @if(array_key_exists("month_max_events_per_day_to_show", $setting))
                    @php
                        $setting_key = 'month_max_events_per_day_to_show';
                    @endphp
                    <div class="col col-12">
                        <div class="form-group">
                            <label for="maxFutureBookingOffset">
                                Maximum events per day to show
                                <span class="small">(month view)</span>
                            </label>
                            <input type="number"
                                max="10"
                                min="1"
                                name="{{$setting_key}}"
                                class="form-control"
                                id="maxFutureBookingOffset"
                                placeholder="Maximum events per day ..."
                                value="@php
                                    if(old($setting_key) !== null){
                                        echo old($setting_key);
                                    }else{
                                        if(old('_token')){
                                            echo '';
                                        }else{
                                            echo $setting[$setting_key];
                                        }
                                    }
                                @endphp">
                            @if($errors->has($setting_key))
                            <div class="small text-danger">
                                {{ $errors->first($setting_key) }}
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
        @endif
        
    </form>
        
@endsection