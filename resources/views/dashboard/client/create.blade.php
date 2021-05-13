<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($client))
            {{ Breadcrumbs::render('client', 'edit - ' . $client->email) }}
        @else
            {{ Breadcrumbs::render('client', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        
        <script src="{{asset('/dists/mc-calendar/mc-calendar.min.js')}}"></script>
        <script>
        
            @if($errors->has('suspend_from'))
                var fromErr = '{{ $errors->first('suspend_from') }}';
            @endif
            
            @if($errors->has('suspend_to'))
                var toErr = '{{ $errors->first('suspend_to') }}';
            @endif
        
            @if(!empty($client))
                let client = @json($client);
            @endif
        
            @if(!empty($validation_messages))
                let validationMessages =  @json($validation_messages);
                // console.log(validationMessages);
            @endif
        
            @if(!empty($suspension))
                let suspensionDB =  @json($suspension);
                // console.log(suspensionDB);
            @endif
            
            @if(!empty($old_suspension))
                var oldSuspension =  @json($old_suspension);
                // console.log('oldSuspension');
                // console.log(oldSuspension);
            @endif
            // console.log(suspensionDB);
            
            // console.log(JSON.parse(JSON.stringify(assignHalls)));
            
            var dataListUrl = '{{ route('dashboard.client.data_list') }}';
            @if(!empty($client))
                var checkEmailUrl = '{{ route('dashboard.client.check_email', ['id' => $client->id]) }}';
            @else
                var checkEmailUrl = '{{ route('dashboard.client.check_email') }}';
            @endif
            
            // console.log(checkEmailUrl);
            
            var phoneTypes = @json($phone_types);
            var indexPrefixes = @json($index_prefixes);
            var phones = @json($phones);
            var currentPhones = @if(!empty($current_phones)) @json($current_phones) @else null @endif;
            
            let createCalendarData = {
                el: '#birthdate',
                dateFormat: 'YYYY-MM-DD',
            }
            
            @if(!empty($client) && !empty($client->birthdate))
                createCalendarData.selectedDate = new Date('{{$client->birthdate}}');
            @endif
            
    		const birthdayCalendar = MCDatepicker.create(createCalendarData);
            birthdayCalendar.onOpen(() => {
                var birthdayCalendarBackground = document.createElement("div");
                birthdayCalendarBackground.className = "birthday-calendar-background";
                birthdayCalendarBackground.setAttribute("id", "birthdayCalendarBackground");
                $("body").append(birthdayCalendarBackground);
                $("#birthdate").blur();
            });
            birthdayCalendar.onClose(() => {
                onCloseBirthdayCalendarModal();
            });
            $("body").on('click', '.birthday-calendar-background', () => {
                birthdayCalendar.close();
                onCloseBirthdayCalendarModal();
            });
            
            function onCloseBirthdayCalendarModal(){
                $("body").find('.birthday-calendar-background').remove();
            }
            
            $(document).ready(function(){
                $('[data-toggle=tooltip').tooltip({
                    boundary: 'window',
                    html: true
                });
            });
    	</script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/tab-switcher.js') }}?{{$rand}}"
            name="tab-switcher"
            form_url="{{ !empty($client) ? route('dashboard.client.update', [$client->id]) : route('dashboard.client.store') }}"
            form_id="clientForm"></script>
        
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/client/client-jquery-validation.js') }}?{{$rand}}"></script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/phone-picker.js') }}?{{$rand}}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/suspension.js') }}?{{$rand}}"></script>
        
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{asset('/dists/mc-calendar/mc-calendar.min.css')}}">
        <style>
            .birthday-calendar-background{
                position: absolute;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0, 0.4);
                z-index: 100;
            }
        </style>
    </x-slot>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <ul class="nav nav-tabs edit-create-nav-tabs" id="clientTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(!Request::has('tab') || Request::get('tab') == 'main') active @endif" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true" tab-name="main">
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
            <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'address') active @endif" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false" tab-name="address">
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
                    <span class="notice-badge notice-badge-success badge badge-pill badge-success @if(empty($phones)) d-none @endif"
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
            <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'pass') active @endif" id="pass-tab" data-toggle="tab" href="#pass" role="tab" aria-controls="pass" aria-selected="false" tab-name="pass">
                Password
                <span id="passwordErrorBadge"
                    class="badge badge-pill badge-danger @if(empty($tab_errors['pass'])) d-none @endif"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="{{!empty($tab_errors) && !empty($tab_errors['pass']) ? $tab_errors['pass'] : ''}} errors">
                        {{!empty($tab_errors['pass']) ? $tab_errors['pass'] : ''}}
                </span>
            </a>
        </li>
        <li class="action-btn">
            
            <button id="submitBtn" class="btn btn-success btn-sm float-right">
                {{ !empty($client) ? 'Update' : 'Create'}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                </svg>
            </button>
            
            @if(!empty($client))
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
                            Delete this client?<br>
                            <form class="pt-1" method="post" action="{{route('dashboard.client.destroy', ['client' => $client->id])}}">
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
            @endif
            
        </li>
    </ul>
    
    <form id="clientForm" action="{{ !empty($client) ? route('dashboard.client.update', [$client->id]) : route('dashboard.client.store') }}" method="post">
        @csrf
        @if(!empty($client))
            @method('PUT')
        @endif
        
        <div class="tab-content edit-create-tab-content" id="clientTabContent">
            <div class="tab-pane fade @if(!Request::has('tab') || Request::get('tab') == 'main') show active @endif" id="main" role="tabpanel" aria-labelledby="main-tab">
                
                <div class="row">
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="email" value="Email*" />
                            <x-input type="text" name="email" id="email" autocomplete="off" :value="
                                (old('email') || $errors->has('email')) ? (old('email') ?? '') : ($client->email ?? '')
                            " />
                            <x-error-small name="email" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="firstName" value="First Name*" />
                            <x-input type="text" name="first_name" id="firstName" :value="
                                (old('first_name') || $errors->has('first_name')) ? (old('first_name') ?? '') : ($client->first_name ?? '')
                            " />
                            <x-error-small name="first_name" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="lastName" value="Last Name" />
                            <x-input type="text" name="last_name" id="lastName" :value="old('last_name') ?? ($client->last_name ?? '')" />
                            <x-error-small name="last_name" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="gender" value="Gender*" />
                            <x-select name="gender"
                                id="gender"
                                :default="$client->gender ?? ''"
                                :options="['male' => 'Male', 'female' => 'Female']"
                                :selected="old('gender') ?? null" />
                            <x-error-small name="gender" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="birthdate" value="Birthdate" />
                            <x-input type="text" name="birthdate" id="birthdate" autocomplete="off" :value="old('birthdate') ?? ($client->birthdate ?? '')" readonly/>
                            <x-error-small name="birthdate" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        
                    </div>
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::get('tab') == 'suspension') show active @endif" id="suspension" role="tabpanel" aria-labelledby="suspension-tab">
                
                <div id="handleSuspension"></div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'address') show active @endif" id="address" role="tabpanel" aria-labelledby="address-tab">
                
                <div class="row">
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="country" value="Country" />
                            <x-input type="text" name="country" id="country" :value="old('country') ?? ($client->country ?? '')" />
                            <x-error-small name="country" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="town" value="Town" />
                            <x-input type="text" name="town" id="town" :value="old('town') ?? ($client->town ?? '')" />
                            <x-error-small name="town" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="street" value="Street" />
                            <x-input type="text" name="street" id="street" :value="old('street') ?? ($client->street ?? '')" />
                            <x-error-small name="street" />
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'phones') show active @endif" id="phones" role="tabpanel" aria-labelledby="phones-tab">
                
                <div id="phonePicker"></div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'pass') show active @endif" id="pass" role="tabpanel" aria-labelledby="pass-tab">
                
                <div class="form-group">
                    <x-label for="password" value="Password*" />
                    <x-input type="password" name="password" id="password" />
                    <x-error-small name="password" />
                </div>
                <div class="form-group">
                    <x-label for="passwordConfirm" value="Password Confirm*" />
                    <x-input type="password" name="password_confirm" id="passwordConfirm" />
                    <x-error-small name="password_confirm" />
                </div>
                
            </div>
        </div>
        
        <input type="submit" style="position: absolute; left: -9999px"/>
    </form>
    
</x-dashboard-layout>