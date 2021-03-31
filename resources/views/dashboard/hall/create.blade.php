<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($hall))
            {{ Breadcrumbs::render('hall', 'edit - ' . $hall->title) }}
        @else
            {{ Breadcrumbs::render('hall', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- <link rel="stylesheet" href="{{ asset('dists/timepicker/timepicker.min.css') }}"> -->
        <!-- <script src="{{ asset('dists/timepicker/timepicker.js') }}?{{rand(100, 1000)}}"></script> -->
        <script type="text/javascript">
            $(function(){
                $("#birthdate").datepicker({
                    dateFormat: "dd-mm-yy"
                });
            });
            
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
        <script src="{{ asset('js/business-hours.js') }}?{{rand(100, 1000)}}"></script>
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
    
    <form action="{{ !empty($hall) ? route('dashboard.hall.update', [$hall->id]) : route('dashboard.hall.store') }}" method="post">
        @csrf
        @if(!empty($hall))
            @method('PUT')
        @endif
        
        <div class="row">
            <div class="col-sm">
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
                
                <div id="usersAssignment"></div>
                
            </div>
            <div class="col-sm">
                
                <div class="form-group">
                    <x-label for="status" value="Status*" />
                    <x-select name="is_closed" id="status"
                        :default="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : 'opened'"
                        :options="['1' => 'Closed', '0' => 'Opened']"
                        :selected="!empty($hall->is_closed) ? ($hall->is_closed == 1 ? 'closed' : 'opened') : null" />
                    <x-error-small name="status" />
                </div>
                
                <div id="businessHours"></div>
                
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
</x-dashboard-layout>