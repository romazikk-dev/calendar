<x-guest-layout>
    <x-auth-card>
        
        <div class="container">
            <div class="row">
                <div class="col-md">
                    <h3 class="text-center pb-4">{{__('text.log_in')}}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    
                    <div class="card">
                        <div class="card-header">
                            Admin
                        </div>
                        <div class="card-body">
                            
                            <div class="login-form">
                                <form action="{{ route('login', ['user']) }}" method="post">
                                    @csrf
                                         
                                    <div class="form-group">
                                        <x-input type="email" name="user_email" placeholder="Email" :value="old('user_email') ?? ''" required autofocus />
                                        <x-error-small name="user_email"></x-error-small>
                                    </div>
                                    <div class="form-group">
                                        <!-- <input type="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required > -->
                                        <x-input type="password" name="user_password"
                                        :placeholder="ucfirst(__('text.password'))"
                                        :value="old('user_password') ?? ''" required />
                                    </div>
                                    <div class="clearfix">
                                        <label class="float-left form-check-label">
                                            <input name="user_remember" type="checkbox" {{ old('user_remember') == 'on' ? 'checked' : '' }}>
                                            {{__('text.remember_me')}}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">
                                            {{__('text.log_in')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                
                </div>
                <div class="col-md">
                
                    <div class="card">
                        <div class="card-header">
                            {{ucfirst(__('text.worker'))}}
                        </div>
                        <div class="card-body">
                            
                            <div class="login-form">
                                <form action="{{ route('login', ['worker']) }}" method="post">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <x-input type="email" name="worker_email" placeholder="Email" :value="old('worker_email') ?? ''" required autofocus />
                                        <x-error-small name="worker_email"></x-error-small>
                                    </div>
                                    <div class="form-group">
                                        <!-- <input type="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required > -->
                                        <x-input type="password" name="worker_password"
                                        :placeholder="ucfirst(__('text.password'))" :value="old('worker_password') ?? ''" required />
                                    </div>
                                    <div class="clearfix">
                                        <label class="float-left form-check-label">
                                            <input name="worker_remember" type="checkbox" {{ old('worker_remember') == 'on' ? 'checked' : '' }}>
                                            {{__('text.remember_me')}}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            {{__('text.log_in')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

    </x-auth-card>
</x-guest-layout>
