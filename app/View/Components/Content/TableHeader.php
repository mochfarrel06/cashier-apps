<?php

namespace App\View\Components\Content;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableHeader extends Component
{
    public $title;
    public $icon;
    public $addRoute;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $icon = null, $addRoute = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->addRoute = $addRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content.table-header');
    }
}
