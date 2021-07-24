@extends('dashboard.settings.view.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['client`s booking calendar', route('dashboard.settings.clients_booking_calendar.index')],
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
    
    @php
        if(old('_token')){
            //dd(old('max_future_booking_offset'));
            //die();
        }
    @endphp
    
    <form action="" method="post">
        @csrf
        <div>
            <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
        </div>
        
        <div class="clearfix"></div>
        @if(!empty($setting))
        <div class="clients-booking-calendar-main-setting">
            <div class="row">
                
                @if(array_key_exists("max_future_booking_offset", $setting))
                    <div class="col col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="maxFutureBookingOffset">Amount of days to maximum available booking date in future</label>
                            <input type="number"
                                name="max_future_booking_offset"
                                class="form-control"
                                id="maxFutureBookingOffset"
                                placeholder="Amount of days ..."
                                value="@php
                                    if(old('max_future_booking_offset') !== null){
                                        echo old('max_future_booking_offset');
                                    }else{
                                        if(old('_token')){
                                            echo '';
                                        }else{
                                            echo $setting['max_future_booking_offset'];
                                        }
                                    }
                                @endphp">
                            @if($errors->has('max_future_booking_offset'))
                            <div class="small text-danger">
                                {{ $errors->first('max_future_booking_offset') }}
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                @if(array_key_exists("time_between_events", $setting))
                    <div class="col col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="timeBetweenEventsInput">Amount of minutes between events</label>
                            <input type="number"
                                name="time_between_events"
                                class="form-control"
                                id="timeBetweenEventsInput"
                                placeholder="Amount of days ..."
                                value="@php
                                    if(old('time_between_events') !== null){
                                        echo old('time_between_events');
                                    }else{
                                        if(old('_token')){
                                            echo '';
                                        }else{
                                            echo $setting['time_between_events'];
                                        }
                                    }
                                @endphp">
                            @if($errors->has('time_between_events'))
                            <div class="small text-danger">
                                {{ $errors->first('time_between_events') }}
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