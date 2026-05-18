<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonForm extends Component
{
    public $form, $backUrl, $variant, $size, $icon, $enableBack;
    /**
     * Create a new component instance.
     */
    public function __construct($form, $enableBack = true, $backUrl = null, $variant = 'success', $size = 'md', $icon = 'ri-save-2-fill')
    {
        $this->form = $form;
        $this->backUrl = $backUrl ?? url()->previous();
        $this->variant = $variant;
        $this->size = $size;
        $this->icon = $icon;
        $this->enableBack = $enableBack;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-form');
    }
}
