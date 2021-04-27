<x-dashboard-layout>
    @if($errors->any())
        @php
            
            $mainErrorsCount = 0;
            $addressErrorsCount = 0;
            
            $mainTabAttrs = ['is_closed','title','description','short_description','notice'];
            $addressTabAttrs = ['country','town','street'];
            
            foreach($mainTabAttrs as $mainTabAttr){
                if($errors->has($mainTabAttr))
                    $mainErrorsCount++;
            }
            
            foreach($addressTabAttrs as $addressTabAttr){
                if($errors->has($addressTabAttr))
                    $addressErrorsCount++;
            }
            
        @endphp
    @endif
    <x-slot name="breadcrumbs">
        @if(!empty($hall))
            {{ Breadcrumbs::render('hall', 'edit - ' . $hall->title) }}
        @else
            {{ Breadcrumbs::render('hall', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
        <!-- <link rel="stylesheet" href="{{ asset('dists/timepicker/timepicker.min.css') }}"> -->
        <!-- <script src="{{ asset('dists/timepicker/timepicker.js') }}?{{rand(100, 1000)}}"></script> -->
        <!-- <script type="text/javascript" src="{{ asset('js/dashboard/halls/create-edit-status.js') }}?{{$rand}}"></script> -->
        
        <script type="text/javascript">
            $(function(){
                $("#birthdate").datepicker({
                    dateFormat: "dd-mm-yy"
                });
            });
            
            let hall = @if(!empty($hall)) @json($hall) @else null @endif;
            // console.log(hall);
            
            @if(old('business_hours'))
                var businessHours = @json(old('business_hours'));
                // console.log(JSON.parse(JSON.stringify(businessHours)));
            @elseif(!empty($business_hours))
                var businessHours = @json($business_hours);
            @endif
            
            var suspension = @if(!empty($suspension)) @json($suspension) @else null @endif;
            
            var oldAssignWorker = @json(old('assign_worker'));
            @if(old('assign_worker'))
                var oldAssignWorker = @json(old('assign_worker'));
                // console.log(JSON.parse(JSON.stringify(oldAssignWorker)));
            @elseif(!empty($assign_workers))
                var oldAssignWorker = @json($assign_workers);
            @endif
            
            var dataListUrl = '{{ route('dashboard.worker.data_list') }}';
            
            @if(!empty($hall))
                var deleteRoute = '{{ route("dashboard.hall.destroy", $hall->id) }}';
            @endif
            
            var hallDataFormUrl = '{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}';
            // var hallEditUrl = '{{ !empty($hall) ? route('dashboard.hall.edit', [$hall->id]) : null }}';
            
            $(document).ready(function(){
                $('.nav-tabs a.nav-link').on('shown.bs.tab', function(){
                    // alert('The new tab is now fully shown.');
                    let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
                    let newHallDataFormUrl = hallDataFormUrl + '?tab=' + tab;
                    $("#hallData").attr('action', newHallDataFormUrl);
                    
                    let currentUrl = location.protocol + '//' + location.host + location.pathname + '?tab=' + tab;
                    window.history.pushState({}, null, currentUrl);
                    
                    console.log('The new tab is now fully shown.');
                });
                
                let params = (new URL(document.location)).searchParams;
                let tab = params.get("tab");
                if(tab != null && tab != 'main'){
                    
                    let newHallDataFormUrl = hallDataFormUrl + '?tab=' + tab;
                    $("#hallData").attr('action', newHallDataFormUrl);
                    // $("#" + tab + "-tab").click();
                }
                
                $('[data-toggle=tooltip').tooltip({
                    boundary: 'window',
                    html: true
                });
                
                // setErrorsCountInTabs();
                // 
                // function setErrorsCountInTabs(){
                //     let main = 0;
                //     let address = 0;
                // 
                //     let mainAttrs = ['is_closed','title','description','short_description','notice'];
                //     let addressAttrs = ['country','town','street'];
                // 
                //     $('.attr-error').each((index) => {
                //         // if()
                //         let attr = $('.attr-error').eq(index).attr('data-attr');
                //         if(mainAttrs.includes(attr))
                //             main++;
                //         if(addressAttrs.includes(attr))
                //             main++;
                //     });
                // 
                //     if(main > 0)
                //         $("#main-tab").append('<span class="badge badge-pill badge-danger">' + main + '</span>');
                //     if(address > 0)
                //         $("#address-tab").append('<span class="badge badge-pill badge-danger">' + address + '</span>');
                //     console.log(main);
                //     console.log(address);
                // }
                // console.log(tab);
            });
        </script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/create-edit-status.js') }}?{{$rand}}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/hall-business-hours.js') }}?{{$rand}}"></script>
        <!-- <script src="{{ asset('js/business-hours.js') }}?{{$rand}}"></script> -->
        <script src="{{ asset('js/worker-assignment-2.js') }}?{{$rand}}"></script>
        
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></link>
    </x-slot>
    
    <x-slot name="after_breadcrumbs">
        <!-- <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">Main</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="worker-tab" data-toggle="tab" href="#worker" role="tab" aria-controls="worker" aria-selected="false">Employees</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="hours-tab" data-toggle="tab" href="#hours" role="tab" aria-controls="hours" aria-selected="false">Business hours</a>
                </li>
            </ul>
        </div> -->
    </x-slot>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <form id="hallData" action="{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}" method="post">
        @csrf
        @if(!empty($hall))
            @method('PUT')
        @endif
        
        <ul class="nav nav-tabs edit-create-nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(!Request::has('tab') || Request::get('tab') == 'main') active @endif" id="main-tab" data-toggle="tab" href="#main" tab-name="main" role="tab" aria-controls="main" aria-selected="true">Main
                    @if(!empty($mainErrorsCount))
                        <span class="badge badge-pill badge-danger">{{$mainErrorsCount}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'suspension') active @endif" id="suspension-tab" data-toggle="tab" href="#suspension" tab-name="suspension" role="tab" aria-controls="suspension" aria-selected="true">
                    Status
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'address') active @endif" id="address-tab" data-toggle="tab" href="#address" tab-name="address" role="tab" aria-controls="address" aria-selected="false">Address
                    @if(!empty($addressErrorsCount))
                        <span class="badge badge-pill badge-danger">{{$addressErrorsCount}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'worker') active @endif" id="worker-tab" data-toggle="tab" href="#worker" tab-name="worker" role="tab" aria-controls="worker" aria-selected="false">
                    Employees
                    @if(empty($assign_workers))
                        <span class="badge badge-pill badge-warning"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="This hall currently has 0 assign employees!">!</span>
                    @else
                        <span class="badge badge-pill badge-success"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="This hall currently has {{count($assign_workers)}} assigned employees!">{{count($assign_workers)}}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'hours') active @endif" id="hours-tab" data-toggle="tab" href="#hours" tab-name="hours" role="tab" aria-controls="hours" aria-selected="false">
                    Business hours
                    @if(!empty($all_days_closed))
                        <span class="badge badge-pill badge-warning"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="All days of week are weekends!">!</span>
                    @else
                        <span class="badge badge-pill badge-success"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="Currently {{$count_opened_days ?? 0}} days opened">{{$count_opened_days ?? 0}}</span>
                    @endif
                </a>
            </li>
            <li class="action-btn">
                
                <button type="submit" class="btn btn-success btn-sm float-right">
                    {{ !empty($hall) ? 'Update' : 'Create'}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                    </svg>
                </button>
                
                @if(!empty($hall))
                
                <div class="dropdown float-right pr-2">
                    <button class="btn btn-danger btn-sm" type="button" id="dropdownDeleteBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Delete
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-1"
                        onclick="event.stopPropagation()"
                        aria-labelledby="dropdownDeleteBtn">
                            Do you want delete this hall?<br>
                            <form class="pt-1" method="post" action="${delete_url}">
                                @csrf
                                @method('delete')
                                <a href="${delete_url}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-primary">
                                        Yes
                                </a>
                                <a href="${delete_url}"
                                    onclick="event.preventDefault(); $('#dropdownDeleteBtn').click();"
                                    class="text-primary"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                        No
                                </a>
                            </form>
                    </div>
                </div>
                
                <!-- <button type="submit" class="btn btn-danger btn-sm">
                    Delete
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                    </svg>
                </button> -->
                @endif
                
            </li>
        </ul>
        
        <div class="edit-create-tab-content tab-content" id="myTabContent">
            <div class="tab-pane fade @if(!Request::has('tab') || Request::get('tab') == 'main') show active @endif" id="main" role="tabpanel" aria-labelledby="main-tab">
                
                <!-- <div class="form-group">
                    <x-label for="status" value="Status" />
                    <x-select name="is_closed" id="status"
                        :default="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : 'opened'"
                        :options="['1' => 'Closed', '0' => 'Opened']"
                        :selected="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : null" />
                    <x-error-small name="status" />
                </div> -->
                <div class="form-group">
                    <x-label for="title" value="Title*" />
                    <x-input type="text" name="title" id="title" :value="
                        (old('title') || $errors->has('title')) ? (old('title') ?? '') : ($hall->title ?? '')
                    " />
                    <x-error-small name="title" />
                </div>
                <div class="form-group">
                    <x-label for="description" value="Description" />
                    <textarea name="description" class="form-control" id="description">{{ old('description') ?? ($hall->description ?? '') }}</textarea>
                    <x-error-small name="description" />
                </div>
                <div class="form-group">
                    <x-label for="shortDescription" value="Short description" />
                    <textarea name="short_description" class="form-control" id="shortDescription">{{ old('short_description') ?? ($hall->short_description ?? '') }}</textarea>
                    <x-error-small name="short_description" />
                </div>
                <div class="form-group">
                    <x-label for="notice" value="Notice" />
                    <textarea name="notice" class="form-control" id="notice">{{ old('notice') ?? ($hall->notice ?? '') }}</textarea>
                    <x-error-small name="notice" />
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::get('tab') == 'suspension') show active @endif" id="suspension" role="tabpanel" aria-labelledby="suspension-tab">
                
                <div id="createEditStatus"></div>
                
            </div>
            <div class="tab-pane fade @if(Request::get('tab') == 'address') show active @endif" id="address" role="tabpanel" aria-labelledby="address-tab">
                
                <div class="form-group">
                    <x-label for="country" value="Country" />
                    <x-input type="text" name="country" id="country" :value="old('country') ?? ($hall->country ?? '')" />
                    <x-error-small name="country" />
                </div>
                <div class="form-group">
                    <x-label for="town" value="Town" />
                    <x-input type="text" name="town" id="town" :value="old('town') ?? ($hall->town ?? '')" />
                    <x-error-small name="town" />
                </div>
                <div class="form-group">
                    <x-label for="street" value="Street" />
                    <x-input type="text" name="street" id="street" :value="old('street') ?? ($hall->street ?? '')" />
                    <x-error-small name="street" />
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::get('tab') == 'worker') show active @endif" id="worker" role="tabpanel" aria-labelledby="worker-tab">
                
                @if(empty($assign_workers))
                    <div class="alert alert-warning" role="alert">
                        This hall currently has <b class="text-uppercase">0</b> assign employees!
                    </div>
                @endif
                <div id="workerAssignment2"></div>
            
            </div>
            <div class="tab-pane fade @if(Request::get('tab') == 'hours') show active @endif" id="hours" role="tabpanel" aria-labelledby="hours-tab">
                
                @if(!empty($all_days_closed))
                    <div class="alert alert-warning" role="alert">
                        All days currently are weekends(<b class="text-uppercase text-danger">closed</b>)
                    </div>
                @endif

                <div id="hallBusinessHours"></div>
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm">
                
                
                <!-- <div id="usersAssignment"></div> -->
                
            </div>
            <div class="col-sm">
                
                <!-- <div class="form-group">
                    <x-label for="status" value="Status*" />
                    <x-select name="is_closed" id="status"
                        :default="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : 'opened'"
                        :options="['1' => 'Closed', '0' => 'Opened']"
                        :selected="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : null" />
                    <x-error-small name="status" />
                </div> -->
                
                
                
            </div>
        </div>
        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
    </form>
    
</x-dashboard-layout>