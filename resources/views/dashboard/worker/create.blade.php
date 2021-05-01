<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($worker))
            {{ Breadcrumbs::render('worker', 'edit - ' . $worker->email) }}
        @else
            {{ Breadcrumbs::render('worker', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        
        <script src="{{asset('/dists/mc-calendar/mc-calendar.min.js')}}"></script>
        <script>
            
            var phoneTypes = @json($phone_types);
            var oldPhones = @if(!empty($phones)) @json($phones) @else @json($old_phones) @endif;
            
            var phoneErrors = @if(\Session::has('phone_errors')) @json(\Session::get('phone_errors')) @else null @endif;
            // console.log(phoneErrors);
            
            let createCalendarData = {
                el: '#birthdate',
                dateFormat: 'YYYY-MM-DD',
            }
            
            @if($worker && !empty($worker->birthdate))
                createCalendarData.selectedDate = new Date('{{$worker->birthdate}}');
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
            
            var workerDataFormUrl = '{{ !empty($worker) ? route('dashboard.worker.update', [$worker->id]) : route('dashboard.worker.store') }}';
            $(document).ready(function(){
                $('.nav-tabs a.nav-link').on('shown.bs.tab', function(){
                    let tab = $('.nav-tabs a.nav-link.active').attr('tab-name');
                    setFormActionAttributeWithTab(tab);
                    
                    let currentUrl = location.protocol + '//' + location.host + location.pathname + '?tab=' + tab;
                    window.history.pushState({}, null, currentUrl);
                    
                    // console.log('The new tab is now fully shown.');
                });
                
                let params = (new URL(document.location)).searchParams;
                
                let tab = params.get("tab");
                if(tab != null && tab != 'main')
                    setFormActionAttributeWithTab(tab);
                    
                function setFormActionAttributeWithTab(tab){
                    let newWorkerDataFormUrl = workerDataFormUrl + '?tab=' + tab;
                    $("#workerForm").attr('action', newWorkerDataFormUrl);
                }
                
                $('[data-toggle=tooltip').tooltip({
                    boundary: 'window',
                    html: true
                });
            });
    	</script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/phone-picker.js') }}?{{$rand}}"></script>
        
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
    
    <ul class="nav nav-tabs edit-create-nav-tabs" id="workerTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(!Request::has('tab') || Request::get('tab') == 'main') active @endif" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true" tab-name="main">
                Main
                @if(!empty($tab_errors['main']))
                    <span class="badge badge-pill badge-danger">{{$tab_errors['main']}}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'address') active @endif" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false" tab-name="address">
                Address
                @if(!empty($tab_errors['address']))
                    <span class="badge badge-pill badge-danger">{{$tab_errors['address']}}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'phones') active @endif" id="phones-tab" data-toggle="tab" href="#phones" role="tab" aria-controls="phones" aria-selected="false" tab-name="phones">
                Phones
                @if(!empty($tab_errors['phones']))
                    <span class="badge badge-pill badge-danger">{{$tab_errors['phones']}}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link @if(Request::has('tab') && Request::get('tab') == 'password') active @endif" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false" tab-name="password">
                Password
                @if(!empty($tab_errors['password']))
                    <span class="badge badge-pill badge-danger">{{$tab_errors['password']}}</span>
                @endif
            </a>
        </li>
        <li class="action-btn">
            
            <button onclick="document.getElementById('workerForm').submit()" class="btn btn-success btn-sm float-right">
                {{ !empty($worker) ? 'Update' : 'Create'}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                </svg>
            </button>
            
            @if(!empty($worker))
            
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
                        Delete this worker?<br>
                        <form class="pt-1" method="post" action="{{route('dashboard.worker.destroy', ['worker' => $worker->id])}}">
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
    
    <form id="workerForm" action="{{ !empty($worker) ? route('dashboard.worker.update', [$worker->id]) : route('dashboard.worker.store') }}" method="post">
        @csrf
        @if(!empty($worker))
            @method('PUT')
        @endif
        
        <div class="tab-content edit-create-tab-content" id="workerTabContent">
            <div class="tab-pane fade @if(!Request::has('tab') || Request::get('tab') == 'main') show active @endif" id="main" role="tabpanel" aria-labelledby="main-tab">
                
                <div class="row">
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="email" value="Email*" />
                            <x-input type="text" name="email" id="email" autocomplete="off" :value="
                                (old('email') || $errors->has('email')) ? (old('email') ?? '') : ($worker->email ?? '')
                            " />
                            <x-error-small name="email" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="firstName" value="First Name*" />
                            <x-input type="text" name="first_name" id="firstName" :value="
                                (old('first_name') || $errors->has('first_name')) ? (old('first_name') ?? '') : ($worker->first_name ?? '')
                            " />
                            <x-error-small name="first_name" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="lastName" value="Last Name" />
                            <x-input type="text" name="last_name" id="lastName" :value="old('last_name') ?? ($worker->last_name ?? '')" />
                            <x-error-small name="last_name" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="gender" value="Gender*" />
                            <x-select name="gender"
                                id="gender"
                                :default="$worker->gender ?? ''"
                                :options="['male' => 'Male', 'female' => 'Female']"
                                :selected="old('gender') ?? null" />
                            <x-error-small name="gender" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="birthdate" value="Birthdate" />
                            <x-input type="text" name="birthdate" id="birthdate" autocomplete="off" :value="old('birthdate') ?? ($worker->birthdate ?? '')" readonly/>
                            <x-error-small name="birthdate" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        
                    </div>
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'address') show active @endif" id="address" role="tabpanel" aria-labelledby="address-tab">
                
                <div class="row">
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="country" value="Country" />
                            <x-input type="text" name="country" id="country" :value="old('country') ?? ($worker->country ?? '')" />
                            <x-error-small name="country" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="town" value="Town" />
                            <x-input type="text" name="town" id="town" :value="old('town') ?? ($worker->town ?? '')" />
                            <x-error-small name="town" />
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-group">
                            <x-label for="street" value="Street" />
                            <x-input type="text" name="street" id="street" :value="old('street') ?? ($worker->street ?? '')" />
                            <x-error-small name="street" />
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'phones') show active @endif" id="phones" role="tabpanel" aria-labelledby="phones-tab">
                
                <div id="phonePicker"></div>
                
            </div>
            <div class="tab-pane fade @if(Request::has('tab') && Request::get('tab') == 'password') show active @endif" id="password" role="tabpanel" aria-labelledby="password-tab">
                
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