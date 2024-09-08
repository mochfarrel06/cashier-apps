<?php

namespace App\View\Components\Content;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableRow extends Component
{
    public $index;
    public $item;
    public $columns;
    public $actions;
    /**
     * Create a new component instance.
     */
    public function __construct($index, $item, $columns, $actions = [])
    {
        $this->index = $index;
        $this->item = $item;
        $this->columns = $columns;
        $this->actions = $actions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content.table-row');
    }
}
