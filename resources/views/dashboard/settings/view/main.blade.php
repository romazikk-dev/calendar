<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @yield('breadcrumbs')
    </x-slot>
    
    <x-slot name="scripts">
        @yield('scripts')
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></link>
        
        @yield('css')
        
    </x-slot>
    
    <div class="container-fluid page-content">
        <div class="setting-page">
            <div class="sp-wrapper">
                
                <!-- <div class="sp-sidebar sp-itm">
                    
                    <ul>
                        
                    </ul>
                    
                </div> -->
                <!-- <div class="sp-content sp-itm"> -->
                <div class="sp-itm">
                    
                    @yield('content')
                    
                    <!-- <form action="" method="post">
                        @csrf
                        @if(!empty($hall))
                            @method('PUT')
                        @endif
                        
                        <button type="submit" class="btn btn-sm btn-success float-right">Save</button>
                        
                        @yield('content')
                    </form> -->
                    
                </div>
                
            </div>
        </div>
    </div>
    
</x-dashboard-layout>