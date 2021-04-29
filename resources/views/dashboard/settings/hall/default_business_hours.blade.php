<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('settings', 'default bussiness hours') }}
    </x-slot>
    
    <x-slot name="scripts">
        <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
        <!-- <link rel="stylesheet" href="{{ asset('dists/timepicker/timepicker.min.css') }}"> -->
        <!-- <script src="{{ asset('dists/timepicker/timepicker.js') }}?{{rand(100, 1000)}}"></script> -->
        <script type="text/javascript">
            // $(function(){
            //     $("#birthdate").datepicker({
            //         dateFormat: "dd-mm-yy"
            //     });
            // });
            
            @if(old('business_hours'))
                var businessHours = @json(old('business_hours'));
                // console.log(JSON.parse(JSON.stringify(businessHours)));
            @elseif(!empty($business_hours))
                var businessHours = @json($business_hours);
            @endif
            
        </script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/hall-business-hours.js') }}?{{$rand}}"></script>
        
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></link>
    </x-slot>
    
    <div class="setting-page">
        <div class="sp-wrapper">
            
            <div class="sp-sidebar sp-itm">
                
                <ul>
                    @foreach(\Setting::getNav() as $nav)
                    <li>
                        <a href="{{$nav['route']}}">{{$nav['title']}}</a>
                    </li>
                    @endforeach
                </ul>
                
            </div>
            <div class="sp-content sp-itm">
                
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
                    @if(!empty($hall))
                        @method('PUT')
                    @endif
                    
                    <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
                    
                    <div id="hallBusinessHours"></div>
                    
                    <!-- <button type="submit" class="btn btn-primary">Apply</button> -->
                </form>
                
            </div>
            
        </div>
    </div>
    
</x-dashboard-layout>