@props(['name'])

@if(!empty($name))
<small {!! $attributes->merge(['class' => 'text-danger']) !!}>
    {{ $errors->first($name) ?? '' }}
</small>
@else
    Attribute name is required
@endif
