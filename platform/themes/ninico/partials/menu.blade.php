<ul {!! $options !!}>
    @foreach ($menu_nodes as $key => $node)
        @php($hasMegaMenu = $node->has_child && count($node->child) > 12)
        <li @class(['has-dropdown' => $node->has_child, 'has-megamenu' => $hasMegaMenu])>
            <a href="{{ url($node->url) }}" @if ($node->target !== '_self') target="{{ $node->target }}" @endif @class(['mega-menu-title' => $hasMegaMenu])>
                @if ($imageIcon = $node->getMetaData('icon_image', true))
                    <img src="{{ RvMedia::getImageUrl($imageIcon) }}" alt="{{ $node->title }}" />
                @elseif ($node->icon_font)
                    <i class="{{ trim($node->icon_font) }}"></i>
                @endif
                {{ $node->title }}
            </a>

            @if ($node->has_child)
                @if ($hasMegaMenu)
                    {!! Menu::generateMenu([
                       'menu' => $node,
                       'menu_nodes' => $node->child,
                       'view' => 'mega-menu',
                       'options' => ['class' => 'submenu mega-menu'],
                   ]) !!}
                @else
                    {!! Menu::generateMenu([
                        'menu' => $node,
                        'menu_nodes' => $node->child,
                        'view' => 'menu',
                        'options' => ['class' => 'submenu'],
                    ]) !!}
                @endif
            @endif
        </li>
    @endforeach
</ul>
