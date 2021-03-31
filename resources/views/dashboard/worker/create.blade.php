<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        @if(!empty($worker))
            {{ Breadcrumbs::render('worker', 'edit - ' . $worker->email) }}
        @else
            {{ Breadcrumbs::render('worker', 'create new') }}
        @endif
    </x-slot>
    
    <x-slot name="scripts">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            $(function(){
                $("#birthdate").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            });
        </script>
    </x-slot>
    
    <x-slot name="styles">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </x-slot>
    
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <form action="{{ !empty($worker) ? route('dashboard.worker.update', [$worker->id]) : route('dashboard.worker.store') }}" method="post">
        @csrf
        @if(!empty($worker))
            @method('PUT')
        @endif
        
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <x-label for="email" value="Email*" />
                    <x-input type="text" name="email" id="email" autocomplete="off" :value="
                        (old('email') || $errors->has('email')) ? (old('email') ?? '') : ($worker->email ?? '')
                    " />
                    <x-error-small name="email" />
                </div>
                <div class="form-group">
                    <x-label for="firstName" value="First Name*" />
                    <x-input type="text" name="first_name" id="firstName" :value="
                        (old('first_name') || $errors->has('first_name')) ? (old('first_name') ?? '') : ($worker->first_name ?? '')
                    " />
                    <x-error-small name="first_name" />
                </div>
                <div class="form-group">
                    <x-label for="lastName" value="Last Name" />
                    <x-input type="text" name="last_name" id="lastName" :value="old('last_name') ?? ($worker->last_name ?? '')" />
                    <x-error-small name="last_name" />
                </div>
                <div class="form-group">
                    <x-label for="gender" value="Gender*" />
                    <x-select name="gender"
                        id="gender"
                        :default="$worker->gender ?? ''"
                        :options="['male' => 'Male', 'female' => 'Female']"
                        :selected="old('gender') ?? null" />
                    <x-error-small name="gender" />
                </div>
                <div class="form-group">
                    <x-label for="phone" value="Phone" />
                    <x-input type="text" name="phone" id="phone" :value="old('phone') ?? ($worker->phone ?? '')" />
                    <x-error-small name="phone" />
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <x-label for="birthdate" value="Birthdate" />
                    <x-input type="text" name="birthdate" id="birthdate" autocomplete="off" :value="old('birthdate') ?? ($worker->birthdate ?? '')" />
                    <x-error-small name="birthdate" />
                </div>
                <div class="form-group">
                    <x-label for="country" value="Country" />
                    <x-input type="text" name="country" id="country" :value="old('country') ?? ($worker->country ?? '')" />
                    <x-error-small name="country" />
                </div>
                <div class="form-group">
                    <x-label for="town" value="Town" />
                    <x-input type="text" name="town" id="town" :value="old('town') ?? ($worker->town ?? '')" />
                    <x-error-small name="town" />
                </div>
                <div class="form-group">
                    <x-label for="street" value="Street" />
                    <x-input type="text" name="street" id="street" :value="old('street') ?? ($worker->street ?? '')" />
                    <x-error-small name="street" />
                </div>
                
                @if(empty($worker))
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
                @endif
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">{{!empty($worker) ? 'Update' : 'Create'}}</button>
        </div>
    </form>
    
</x-dashboard-layout>