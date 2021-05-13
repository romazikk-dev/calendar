<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($template))
            {{ Breadcrumbs::render('template', 'edit - ' . $template->title) }}
        @else
            {{ Breadcrumbs::render('template', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            @if(old('business_hours'))
                var businessHours = @json(old('business_hours'));
                // console.log(JSON.parse(JSON.stringify(businessHours)));
            @elseif(!empty($business_hours))
                var businessHours = @json($business_hours);
            @endif
            
            var oldAssignWorker = @json(old('assign_worker'));
            @if(old('assign_worker'))
                var oldAssignWorker = @json(old('assign_worker'));
                // console.log(JSON.parse(JSON.stringify(oldAssignWorker)));
            @elseif(!empty($assign_workers))
                var oldAssignWorker = @json($assign_workers);
            @endif
            
            var dataListUrl = '{{ route('dashboard.worker.data_list') }}';
        </script>
        <script src="{{ asset('js/time-picker.js') }}?{{rand(100, 1000)}}"></script>
        <script src="{{ asset('js/users-assignment.js') }}?{{rand(100, 1000)}}"></script>
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></link>
    </x-slot>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <form action="{{ !empty($template) ? route('dashboard.template.update', [$template->id]) : route('dashboard.template.store') }}" method="post">
        @csrf
        @if(!empty($template))
            @method('PUT')
        @endif
        
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <x-label for="title" value="Title*" />
                    <x-input type="text" name="title" id="title" :value="
                        (old('title') || $errors->has('title')) ? (old('title') ?? '') : ($template->title ?? '')
                    " />
                    <x-error-small name="title" />
                </div>
                
                <div class="form-group">
                    <x-label for="price" value="Price*" />
                    <x-input type="text" name="price" id="price" :value="
                        (old('price') || $errors->has('price')) ? (old('price') ?? '') : ($template->price ?? '')
                    " />
                    <x-error-small name="price" />
                </div>
                
                <div class="form-group">
                    {{$template->duration_parsed}}
                    <x-label for="duration" value="Duration*" />
                    <div id="timePicker"
                            data-name="duration"
                            data-id="duration"
                            data-value="@php
                                echo (old('duration') || $errors->has('duration')) ? (old('duration') ?? '') : ($template->duration_parsed ?? '');
                            @endphp"></div>
                    <x-error-small name="duration" />
                </div>
                
                <div id="usersAssignment"></div>
                
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <x-label for="description" value="Description" />
                    <textarea name="description" class="form-control" id="description">{{ old('description') ?? ($template->description ?? '') }}</textarea>
                    <x-error-small name="description" />
                </div>
                <div class="form-group">
                    <x-label for="shortDescription" value="Short description" />
                    <textarea name="short_description" class="form-control" id="shortDescription">{{ old('short_description') ?? ($template->short_description ?? '') }}</textarea>
                    <x-error-small name="short_description" />
                </div>
                <div class="form-group">
                    <x-label for="notice" value="Notice" />
                    <textarea name="notice" class="form-control" id="notice">{{ old('notice') ?? ($template->notice ?? '') }}</textarea>
                    <x-error-small name="notice" />
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
</x-dashboard-layout>