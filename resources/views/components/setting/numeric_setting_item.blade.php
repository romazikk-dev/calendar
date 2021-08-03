@props([
    'setting',
    'settingKey',
    'inputId',
    'inputMin' => 1,
    'inputMax' => 1000,
    'inputPlaceholder',
    'infoBadgeLabel',
    'label'
])

<div class="col col-12">
    <div class="form-group">
        @if(!empty($label))
        <label for="{{$inputId}}">
            {{$label}}
            @if(!empty($infoBadgeLabel))
                <span class="badge badge-pill badge-info">{{$infoBadgeLabel}}</span>
            @endif
        </label>
        @endif
        <input type="number"
            max="{{$inputMax}}"
            min="{{$inputMin}}"
            name="{{$settingKey}}"
            class="form-control"
            id="{{$inputId}}"
            @if(!empty($inputPlaceholder)) placeholder="{{$inputPlaceholder}}" @endif
            value="@php
                if(old($settingKey) !== null){
                    echo old($settingKey);
                }else{
                    if(old('_token')){
                        echo '';
                    }else{
                        echo $setting[$settingKey];
                    }
                }
            @endphp">
        @if($errors->has($settingKey))
        <div class="small text-danger">
            {{ $errors->first($settingKey) }}
        </div>
        @endif
    </div>
</div>