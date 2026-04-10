<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public $ajax;
    public $columns;
    public $id;
    public $class;
    
    /**
     * Create a new component instance.
     */
    public function __construct($id, $ajax, $columns = 'datatable', $class = null)
    {
        $this->id = $id;
        $this->ajax = $ajax;
        $this->columns = $columns;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
