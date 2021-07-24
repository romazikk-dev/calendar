@extends('dashboard.settings.view.main')

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
                    <x-setting.numeric_setting_item
                        :setting="$setting"
                        label="Maximum events per day to show"
                        setting_key="month_max_events_per_day_to_show"
                        input_id="monthMaxEventsPerDayToShow"
                        input_placeholder="Maximum events per day ..."
                        view="month" />
                @endif
                
                @if(array_key_exists("week_max_events_per_day_to_show", $setting))
                <x-setting.numeric_setting_item
                    :setting="$setting"
                    label="Maximum events per day to show"
                    setting_key="week_max_events_per_day_to_show"
                    input_id="weekMaxEventsPerDayToShow"
                    input_placeholder="Maximum events per day ..."
                    view="week" />
                @endif
                
                @if(array_key_exists("day_max_events_per_day_to_show", $setting))
                <x-setting.numeric_setting_item
                    :setting="$setting"
                    label="Maximum events per day to show"
                    setting_key="day_max_events_per_day_to_show"
                    input_id="dayMaxEventsPerDayToShow"
                    input_placeholder="Maximum events per day ..."
                    view="day" />
                @endif
                
                @if(array_key_exists("list_max_events_per_day_to_show", $setting))
                <x-setting.numeric_setting_item
                    :setting="$setting"
                    label="Maximum events per day to show"
                    setting_key="list_max_events_per_day_to_show"
                    input_id="listMaxEventsPerDayToShow"
                    input_placeholder="Maximum events per day ..."
                    view="list" />
                @endif
                
            </div>
        </div>
        @endif
        
    </form>
        
@endsection