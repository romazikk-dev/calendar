@unless ($breadcrumbs->isEmpty())

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if (!is_null($breadcrumb->url) && !$loop->last)
                <li class="breadcrumb-item">
                    @if (!empty($breadcrumb->icon))
                        {!! $breadcrumb->icon !!}
                    @endif
                    <a href="{{ $breadcrumb->url }}">
                        @if (!empty($breadcrumb->img))
                            {!! $breadcrumb->img !!}
                        @else
                            {{ $breadcrumb->title }}
                        @endif
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active">
                    @if (!empty($breadcrumb->icon))
                        {!! $breadcrumb->icon !!}
                    @endif
                    @if (!empty($breadcrumb->img))
                        {!! $breadcrumb->img !!}
                    @else
                        {{ $breadcrumb->title }}
                    @endif
                </li>
            @endif

        @endforeach
    </ol>

@endunless