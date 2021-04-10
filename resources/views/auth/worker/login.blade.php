<x-guest-layout>
    <x-auth-card>
        
        <div class="login-form">
            <form action="{{ route('worker.login') }}" method="post">
                @csrf
                
                <h2 class="text-center">Worker Log in</h2>       
                <div class="form-group">
                    <x-input type="email" name="email" placeholder="Email" :value="old('country') ?? ''" required autofocus />
                    <x-error-small name="email"></x-error-small>
                </div>
                <div class="form-group">
                    <!-- <input type="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required > -->
                    <x-input type="password" name="password" placeholder="Password" :value="old('password') ?? ''" required />
                </div>
                <div class="clearfix">
                    <label class="float-left form-check-label">
                        <input name="remember" type="checkbox" {{ old('remember') == 'on' ? 'checked' : '' }}> Remember me
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Log in</button>
                </div>
            </form>
        </div>

    </x-auth-card>
</x-guest-layout>
