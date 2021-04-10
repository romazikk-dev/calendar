<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('worker', 'edit password - ' . $worker->email) }}
    </x-slot>
    
    <form action="{{ route('dashboard.worker.update_password', [$worker->id]) }}" method="post">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-sm-4 mx-auto">
                
                <div class="alert alert-info" role="alert">
                    Name: {{$worker->full_name}}<br>
                    Email: {{$worker->email}}
                </div>
                
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Password successfuly changed.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                <div class="form-group">
                    <x-label for="password" value="New Password*" />
                    <x-input type="password" name="password" id="password" />
                    <x-error-small name="password" />
                </div>
                <div class="form-group">
                    <x-label for="passwordConfirm" value="New Password Confirm*" />
                    <x-input type="password" name="password_confirm" id="passwordConfirm" />
                    <x-error-small name="password_confirm" />
                </div>
                
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </div>
    </form>
    
</x-dashboard-layout>