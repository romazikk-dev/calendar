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
                
                @if(array_key_exists("calendar_alias", $setting))
                    <x-setting.string_setting_item
                        :setting="$setting"
                        label="Alias of calendar"
                        setting_key="calendar_alias"
                        input_id="calendarAliasInput"
                        input_placeholder="Alias ..."
                        :info_badge_label="route('calendar.booking.alias', [$setting['calendar_alias']])" />
                @endif
                
                @if(array_key_exists("max_future_booking_offset", $setting))
                    <x-setting.numeric_setting_item
                        :setting="$setting"
                        label="Amount of days to maximum available booking date in future"
                        setting_key="max_future_booking_offset"
                        input_id="maxFutureBookingOffset"
                        input_placeholder="Amount of days ..." />
                @endif
                
                @if(array_key_exists("time_between_events", $setting))
                    <x-setting.numeric_setting_item
                        :setting="$setting"
                        label="Amount of minutes between events"
                        setting_key="time_between_events"
                        input_id="timeBetweenEventsInput"
                        input_placeholder="Amount of days ..." />
                @endif
                
            </div>
        </div>
        @endif
        
    </form>
        
@endsection