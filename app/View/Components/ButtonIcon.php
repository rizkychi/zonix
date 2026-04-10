<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonIcon extends Component
{
    public $type, $variant, $size, $icon;
    /**
     * Create a new component instance.
     */
    public function __construct($type = 'button', $variant = 'primary', $size = 'sm', $icon = null)
    {
        $this->type = $type;
        $this->variant = $variant;
        $this->size = $size;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-icon');
    }
}
