@props(['options','default','disabled' => false,'selected'])

@if(!empty($attributes['name']))
<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}>
    <option value="" class="d-none">------</option>
    @if(!empty($options))
        @foreach ($options as $k => $v)
            <option value="{{$k}}"
                    @if(empty($selected) && (int)old($attributes['name']) === $k)
                        selected
                    @elseif(!empty($selected) && strtolower($v) == strtolower($selected))
                        selected
                    @elseif(empty(old($attributes['name'])) && isset($default) && strtolower($v) == strtolower($default))
                        selected
                    @endif
                    >{{$v}}</option>
        @endforeach
    @endif
</select>
@else
    Attribute name is required
@endif