@props(['name'])

@if($errors->has($name))
    @if(!empty($name))
    <small data-attr="@php echo $name @endphp" {!! $attributes->merge(['class' => 'text-danger attr-error']) !!}>
        {{ $errors->first($name) ?? '' }}
    </small>
    @else
        Attribute name is required
    @endif
@endif
