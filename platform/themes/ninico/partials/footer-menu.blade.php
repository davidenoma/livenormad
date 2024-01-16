<ul {!! $options !!}>
    @foreach ($menu_nodes->loadMissing('metadata') as $key => $row)
        <li @if ($row->css_class) class="{{ $row->css_class }} @endif>
            <a
                href="{{ url($row->url) }}"
                title="{{ $row->title }}"
                @if ($row->target !== '_self') target="{{ $row->target }}" @endif
            >
                @if ($iconImage = $row->getMetaData('icon_image', true))
                    <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $row->title }}" width="14" height="14"/>
                @elseif ($row->icon_font)
                    <i class="{{ trim($row->icon_font) }}"></i>
                @endif
                {{ $row->title }}
            </a>
        </li>
    @endforeach
</ul>
