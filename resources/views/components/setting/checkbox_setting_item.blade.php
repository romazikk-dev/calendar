@props([
    'setting',
    'settingKey',
    'inputId',
    'infoBadgeLabel',
    'label'
])

<div class="col col-12">
    <div class="form-group form-group-for-checkbox">
        @if(!empty($label))
        <label for="{{$inputId}}">
            {{$label}}
            @if(!empty($infoBadgeLabel))
                <span class="badge badge-pill badge-info">{{$infoBadgeLabel}}</span>
            @endif
        </label>
        @endif
        <input type="checkbox"
            name="{{$settingKey}}"
            class="form-control"
            id="{{$inputId}}"
            @if(old($settingKey) !== null || $setting[$settingKey] == true) checked @endif
    </div>
</div>