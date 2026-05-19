<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $name;
    public $label;
    public $value;
    public $placeholder;
    public $required;
    public $size;
    public $class;
    public $disabled;
    public $readonly;

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'text', $name = '', $label = '', $value = '', $placeholder = '', $required = false, $size = 'md', $class = '', $disabled = false, $readonly = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->size = $size;
        $this->class = $class;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
