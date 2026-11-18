<?php

namespace Prairie\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuTitle extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $iconClasses = null,
    ) {
        $this->uuid = "prairie" . md5(serialize($this)) . $id;
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                <li {{ $attributes->class(["menu-title"]) }}>
                    <div class="flex items-center gap-2">

                        @if($icon)
                            <x-prairie-icon :name="$icon" @class([$iconClasses]) />
                        @endif

                        {{ $title }}
                    </div>
                </li>
            BLADE;
    }
}
