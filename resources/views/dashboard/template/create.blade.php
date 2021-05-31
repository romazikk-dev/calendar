<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($template))
            {{ Breadcrumbs::render('template', 'edit - ' . $template->title) }}
        @else
            {{ Breadcrumbs::render('template', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        
        <script type="text/javascript">
            let specifics = @json($specifics);
            
            @if(!empty($picked_specific))
                let pickedSpecific = @json($picked_specific);
            @else
                let pickedSpecific = null;
            @endif
        
            @if(!empty($template))
                let template = @json($template);
            @endif
        
            @if(!empty($validation_messages))
                let validationMessages =  @json($validation_messages);
                console.log(validationMessages);
            @endif
            
            var assignWorkers = @if(!empty($assign_workers)) @json($assign_workers) @else null @endif;
            
            var dataListUrl = '{{ route('dashboard.worker.data_list') }}';
            
            @if(!empty($template))
                var deleteRoute = '{{ route("dashboard.hall.destroy", $template->id) }}';
            @endif
            
            $(document).ready(function(){
                $('[data-toggle=tooltip').tooltip({
                    boundary: 'window',
                    html: true
                });
            });
            
        </script>
        
        <script type="text/javascript" src="{{ asset('js/dashboard/tab-switcher.js') }}?{{$rand}}"
            name="tab-switcher"
            form_url="{{ !empty($template) ? route('dashboard.hall.update', [$template->id]) : route('dashboard.hall.store') }}"
            form_id="hallForm"></script>
        
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard/template/template-jquery-validation.js') }}?{{$rand}}"></script>
        <script src="{{ asset('js/dashboard/worker-assignment.js') }}?{{$rand}}"></script>
        <script src="{{ asset('js/dashboard/specific-assignment.js') }}?{{$rand}}"></script>
        <script src="{{ asset('js/dashboard/time-picker.js') }}?{{$rand}}"></script>
        
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
    
    @if($errors->any())
        {{var_dump($errors->all())}}
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
            <li class="nav-item d-none" role="presentation">
                <a class="nav-link @if(Request::get('tab') == 'specifics') active @endif" id="specifics-tab" data-toggle="tab" href="#specifics" tab-name="specifics" role="tab" aria-controls="specifics" aria-selected="false">
                    <!-- <span class="notice-badges">
                        <span class="notice-badge notice-badge-warning text-warning d-none"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="0 specifics">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </span>
                        <span class="notice-badge notice-badge-success badge badge-pill badge-info d-none"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="This hall currently has {{!empty($assign_worker) ? count($assign_worker) : 0}} assigned employees!">{{!empty($assign_worker) ? count($assign_worker) : 0}}</span>
                    </span> -->
                    Specifics
                    <span id="specificsErrorBadge"
                        class="error-badge badge badge-pill badge-danger @if(empty($tab_errors['specific'])) d-none @endif"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="{{!empty($tab_errors) && !empty($tab_errors['specific']) ? $tab_errors['specific'] : ''}} errors">
                        {{!empty($tab_errors) && !empty($tab_errors['specific']) ? $tab_errors['specific'] : ''}}
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
                            title="This hall currently has {{!empty($assign_worker) ? count($assign_worker) : 0}} assigned employees!">{{!empty($assign_worker) ? count($assign_worker) : 0}}</span>
                    </span>
                    Employees
                </a>
            </li>
            <li class="action-btn">
                
                <button id="submitBtn" class="btn btn-success btn-sm float-right">
                    {{ !empty($template) ? 'Update' : 'Create'}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"/>
                    </svg>
                </button>
                
                @if(!empty($template))
                
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
                                    <form class="pt-1" method="post" action="{{route('dashboard.hall.destroy', [$template->id])}}">
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
        
        <form id="templateForm" action="{{ !empty($template) ? route('dashboard.template.update', [$template->id]) : route('dashboard.template.store') }}" method="post">
            @csrf
            @if(!empty($template))
                @method('PUT')
            @endif
            
            <div class="edit-create-tab-content tab-content" id="myTabContent">
                <div class="tab-pane fade @if(!Request::has('tab') || Request::get('tab') == 'main') show active @endif" id="main" role="tabpanel" aria-labelledby="main-tab">
                    
                    <div class="specifics-assignment">
                        <div id="specificAssignmentApp"></div>
                    </div>
                    
                    <div class="form-group">
                        <x-label for="title" value="Title*" />
                        <x-input type="text" name="title" id="title" :value="
                            (old('title') || $errors->has('title')) ? (old('title') ?? '') : ($template->title ?? '')
                        " />
                        <x-error-small name="title" />
                    </div>
                    <div class="form-group">
                        {{$template->duration_parsed ?? ''}}
                        <x-label for="duration" value="Duration*" />
                        <div id="timePicker"
                                data-name="duration"
                                data-id="duration"
                                data-max="5"
                                data-value="@php
                                    echo (old('duration') || $errors->has('duration')) ? (old('duration') ?? '') : ($template->duration_parsed ?? '');
                                @endphp"></div>
                        <x-error-small name="duration" />
                    </div>
                    <div class="form-group">
                        <x-label for="price" value="Price" />
                        <x-input type="text" name="price" id="price" :value="
                            (old('price') || $errors->has('price')) ? (old('price') ?? '') : ($template->price ?? '')
                        " />
                        <x-error-small name="price" />
                    </div>
                    <div class="form-group">
                        <x-label for="description" value="Description" />
                        <textarea name="description" class="form-control" id="description">{{ old('description') ?? ($template->description ?? '') }}</textarea>
                        <x-error-small name="description" />
                    </div>
                    <div class="form-group">
                        <x-label for="short_description" value="Short description" />
                        <textarea name="short_description" class="form-control" id="short_description">{{ old('short_description') ?? ($template->short_description ?? '') }}</textarea>
                        <x-error-small name="short_description" />
                    </div>
                    <div class="form-group">
                        <x-label for="notice" value="Notice" />
                        <textarea name="notice" class="form-control" id="notice">{{ old('notice') ?? ($template->notice ?? '') }}</textarea>
                        <x-error-small name="notice" />
                    </div>
                    
                </div>
                <!-- <div class="tab-pane fade @if(Request::get('tab') == 'specifics') show active @endif" id="specifics" role="tabpanel" aria-labelledby="specifics-tab">
                    
                    <div class="specifics-assignment">
                        <div id="specificAssignmentApp"></div>
                    </div>
                
                </div> -->
                <div class="tab-pane fade @if(Request::get('tab') == 'worker') show active @endif" id="worker" role="tabpanel" aria-labelledby="worker-tab">
                    
                    <div id="workerAssignment2"></div>
                
                </div>
            </div>
            
            <input type="submit" style="position: absolute; left: -9999px"/>
        </form>
    
</x-dashboard-layout>