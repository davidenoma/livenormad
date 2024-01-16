<ul {!! $options !!}>
    @foreach ($menu_nodes as $key => $node)
    @php
        $title = $node->title;
    @endphp

        <li @class(['has-dropdown' => $node->has_child])>
            <a href="{{ url($node->url) }}" @if ($node->target !== '_self') target="{{ $node->target }}" @endif>
                @if ($imageIcon = $node->getMetaData('icon_image', true))
                    {{ RvMedia::image($imageIcon, $title, attributes: ['class' => $title ? 'me-1' : '']) }}
                @elseif ($node->icon_font)
                    <i @class([trim($node->icon_font), 'me-1' => $title])></i>
                @endif

                @if ($title)
                    <span class="title">{{ $title }}</span>
                @endif
            </a>

            @if ($node->has_child)
                {!! Menu::generateMenu([
                    'menu' => $node,
                    'menu_nodes' => $node->child,
                    'view' => 'menu',
                    'options' => ['class' => 'submenu'],
                ]) !!}
            @endif
        </li>
    @endforeach
</ul>
