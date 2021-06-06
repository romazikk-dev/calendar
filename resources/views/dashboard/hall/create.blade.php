<x-dashboard-layout>
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
        
            @if($errors->has('suspend_from'))
                var fromErr = '{{ $errors->first('suspend_from') }}';
            @endif
            
            @if($errors->has('suspend_to'))
                var toErr = '{{ $errors->first('suspend_to') }}';
            @endif
        
            @if(!empty($hall))
                let hall = @json($hall);
            @endif
        
            @if(!empty($validation_messages))
                let validationMessages =  @json($validation_messages);
                // console.log(validationMessages);
            @endif
            
            
            @if(old('business_hours'))
                var businessHours = @json(old('business_hours'));
                // console.log(JSON.parse(JSON.stringify(businessHours)));
            @elseif(!empty($business_hours))
                var businessHours = @json($business_hours);
            @endif
            
            @if(!empty($suspension))
                let suspensionDB =  @json($suspension);
                // console.log(suspensionDB);
            @endif
            
            @if(!empty($holidays))
                let holidaysData =  @json($holidays);
                // console.log(holidaysData);
            @endif
            
            @if(!empty($old_suspension))
                var oldSuspension =  @json($old_suspension);
                // console.log('oldSuspension');
                // console.log(oldSuspension);
            @endif
            
            var assignWorkers = @if(!empty($assign_workers)) @json($assign_workers) @else null @endif;
            
            var phoneTypes = @json($phone_types);
            var indexPrefixes = @json($index_prefixes);
            var phones = @json($phones);
            
            var dataListUrl = '{{ route('dashboard.worker.data_list') }}';
            
            @if(!empty($hall))
                var checkHolidaysUrl = `{{ route('dashboard.hall.check_holidays', [
                    'id' => $hall->id
                ]) }}`;
                // console.log(checkHolidayPeriodUrl);
            @endif
            
            @if(!empty($hall))
                var deleteRoute = '{{ route("dashboard.hall.destroy", $hall->id) }}';
            @endif
            
            // var hallFormUrl = '{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}';
            
            $(document).ready(function(){
                $('[data-toggle=tooltip').tooltip({
                    boundary: 'window',
                    html: true
                });
            });
            
        </script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/tab-switcher.js') }}?{{$rand}}"
            name="tab-switcher"
            form_url="{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}"
            form_id="hallForm"></script>
        
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/halls/hall-jquery-validation.js') }}?{{$rand}}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/suspension.js') }}?{{$rand}}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/business-hours.js') }}?{{$rand}}"></script>
        <script src="{{ asset('js/dashboard/worker-assignment.js') }}?{{$rand}}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/phone-picker.js') }}?{{$rand}}"></script>
        <script src="{{ asset('js/dashboard/holidays.js') }}?{{$rand}}"></script>
        <!-- <script type="text/javascript" src="{{ asset('js/dashboard/timezone-picker.js') }}?{{$rand}}"></script> -->
        
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></link>
    </x-slot>
    
    <x-slot name="after_breadcrumbs">
        
    </x-slot>
    
    @if (session('success'))
    <div class="alert-form-success alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
        
        <ul class="nav nav-tabs edit-create-nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(!Request::has('tab') || Request::get('tab') == 'main') active @endif" id="main-tab" data-toggle="tab" href="#main" tab-name="main" role="tab" aria-controls="main" aria-selected="true">
                    Main
                    <span id="mainErrorBadge"
                        class="error-badge badge badge-pill badge-danger @if(empty($tab_errors['main'])) d-none @endif"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="{{!empty($tab_errors) && !empty($tab_errors['main']) ? $tab_errors['main'] : ''}} errors">
                        {{!empty($tab_errors) && !empty($tab_errors['main']) ? $tab_errors['main'] : ''}}
                    </span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'suspension') active @endif" id="suspension-tab" data-toggle="tab" href="#suspension" tab-name="suspension" role="tab" aria-controls="suspension" aria-selected="true">
                    Status
                    <span id="statusErrorBadge"
                        class="badge badge-pill badge-danger @if(empty($old_suspension['count_status_error'])) d-none @endif"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="{{!empty($old_suspension['count_status_error']) ? $old_suspension['count_status_error'] : 0}} errors">
                            {{!empty($old_suspension['count_status_error']) ? $old_suspension['count_status_error'] : 0}}
                    </span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'address') active @endif" id="address-tab" data-toggle="tab" href="#address" tab-name="address" role="tab" aria-controls="address" aria-selected="false">
                    Address
                    <span id="addressErrorBadge"
                        class="badge badge-pill badge-danger @if(empty($tab_errors['address'])) d-none @endif"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="{{!empty($tab_errors) && !empty($tab_errors['address']) ? $tab_errors['address'] : ''}} errors">
                            {{!empty($tab_errors['address']) ? $tab_errors['address'] : ''}}
                    </span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'phones') active @endif" id="phones-tab" data-toggle="tab" href="#phones" role="tab" aria-controls="phones" aria-selected="false" tab-name="phones">
                    <span class="notice-badges">
                        <span class="notice-badge notice-badge-success badge badge-pill badge-info @if(empty($phones)) d-none @endif"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="{{!empty($phones) ? count($phones) : 0}} phones">
                                {{!empty($phones) ? count($phones) : 0}}
                        </span>
                    </span>
                    Phones
                    <span id="phoneErrorBadge"
                        class="badge badge-pill badge-danger @if(empty($tab_errors['phones'])) d-none @endif"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="{{!empty($tab_errors) && !empty($tab_errors['phones']) ? $tab_errors['phones'] : ''}} errors">
                            {{!empty($tab_errors['phones']) ? $tab_errors['phones'] : ''}}
                    </span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'worker') active @endif" id="worker-tab" data-toggle="tab" href="#worker" tab-name="worker" role="tab" aria-controls="worker" aria-selected="false">
                    <span class="notice-badges">
                        <span class="notice-badge notice-badge-warning text-warning d-none"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="0 employees">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </span>
                        <span class="notice-badge notice-badge-success badge badge-pill badge-info d-none"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="{{!empty($assign_workers) ? count($assign_workers) : 0}} employees!">
                                {{!empty($assign_workers) ? count($assign_workers) : 0}}
                        </span>
                    </span>
                    Employees
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'hours') active @endif" id="hours-tab" data-toggle="tab" href="#hours" tab-name="hours" role="tab" aria-controls="hours" aria-selected="false">
                    <span class="notice-badges">
                        <span class="notice-badge notice-badge-warning text-warning @if(!empty($count_workdays)) d-none @endif"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="All days are weekends">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </span>
                        <span class="notice-badge notice-badge-success badge badge-pill badge-info @if(empty($count_workdays)) d-none @endif"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="{{$count_workdays ?? 0}} days opened">{{$count_workdays ?? 0}}</span>
                    </span>
                    Business hours
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'holidays') active @endif" id="holidays-tab" data-toggle="tab" href="#holidays" tab-name="holidays" role="tab" aria-controls="holidays" aria-selected="false">
                    <span class="notice-badges">
                        <span class="notice-badge notice-badge-success badge badge-pill badge-info @if(empty($holidays)) d-none @endif"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="{{!empty($holidays) ? count($holidays) : 0}} holidays">{{!empty($holidays) ? count($holidays) : 0}}</span>
                    </span>
                    Holidays
                </a>
            </li>
            <li class="action-btn">
                
                <button id="submitBtn" class="btn btn-success btn-sm float-right">
                    {{ !empty($hall) ? 'Apply' : 'Create'}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                    </svg>
                </button>
                
                @if(!empty($hall))
                
                    <div class="action-drop dropdown float-right pr-2">
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
                                <div class="btnns">
                                    <form class="pt-1" method="post" action="{{route('dashboard.hall.destroy', [$hall->id])}}">
                                        @csrf
                                        @method('delete')
                                        <a href="#"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="btnn text-primary">
                                                Yes
                                        </a>
                                        <a href="#"
                                            onclick="event.preventDefault(); $('#dropdownDeleteBtn').click();"
                                            class="btnn text-primary"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                                No
                                        </a>
                                    </form>
                                </div>
                        </div>
                    </div>
                
                @endif
                
            </li>
        </ul>
        
        <form id="hallForm" action="{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}" method="post">
            @csrf
            @if(!empty($hall))
                @method('PUT')
            @endif
            
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
                    
                    <div id="handleSuspension"></div>
                    
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
                <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'phones') show active @endif" id="phones" role="tabpanel" aria-labelledby="phones-tab">
                    
                    <div id="phonePicker"></div>
                    
                </div>
                <div class="tab-pane fade @if(Request::get('tab') == 'worker') show active @endif" id="worker" role="tabpanel" aria-labelledby="worker-tab">
                    
                    <div id="workerAssignment2"></div>
                
                </div>
                <div class="tab-pane fade @if(Request::get('tab') == 'hours') show active @endif" id="hours" role="tabpanel" aria-labelledby="hours-tab">
                    
                    @if(!empty($all_days_closed))
                        <div class="alert alert-warning" role="alert">
                            All days currently are weekends(<b class="text-uppercase text-danger">closed</b>)
                        </div>
                    @endif

                    <div id="businessHours"></div>
                    
                </div>
                <div class="tab-pane fade @if(Request::get('tab') == 'holidays') show active @endif" id="holidays" role="tabpanel" aria-labelledby="holidays-tab">
                    
                    @if(!empty($all_days_closed))
                        <div class="alert alert-warning" role="alert">
                            All days currently are weekends(<b class="text-uppercase text-danger">closed</b>)
                        </div>
                    @endif
                    
                    <div id="holidaysApp"></div>
                    
                </div>
            </div>
            
            <input type="submit" style="position: absolute; left: -9999px"/>
        </form>
    
</x-dashboard-layout>