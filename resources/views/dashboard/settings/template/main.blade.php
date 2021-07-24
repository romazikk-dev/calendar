@extends('dashboard.settings.view.main')

@section('breadcrumbs')
    {{ Breadcrumbs::render('settings', [
        ['template', route('dashboard.settings.template.index')],
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
                
                @if(array_key_exists("duration_range", $setting))
                    <div class="col-12">
                        <h5>
                            Duration range:
                        </h5>
                        <span class="small">(Applyes while creating new or editing already existing one template or editing an event)<span>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="durationRangeStart">Minimum: <span class="small">(minutes)<span></label>
                            <input type="number"
                                min="0"
                                name="duration_range_start"
                                class="form-control"
                                id="durationRangeStart"
                                placeholder="Min minutes ..."
                                value="@php
                                    if(old('duration_range_start') !== null){
                                        echo old('duration_range_start');
                                    }else{
                                        if(old('_token')){
                                            echo '';
                                        }else{
                                            echo $setting['duration_range']['start'];
                                        }
                                    }
                                @endphp">
                            @if($errors->has('duration_range_start'))
                            <div class="small text-danger">
                                {{ $errors->first('duration_range_start') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="durationRangeEnd">Maximum: <span class="small">(minutes)<span></label>
                            <input type="number"
                                min="0"
                                name="duration_range_end"
                                class="form-control"
                                id="durationRangeEnd"
                                placeholder="Max minutes ..."
                                value="@php
                                    if(old('duration_range_end') !== null){
                                        echo old('duration_range_end');
                                    }else{
                                        if(old('_token')){
                                            echo '';
                                        }else{
                                            echo $setting['duration_range']['end'];
                                        }
                                    }
                                @endphp">
                            @if($errors->has('duration_range_end'))
                            <div class="small text-danger">
                                {{ $errors->first('duration_range_end') }}
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