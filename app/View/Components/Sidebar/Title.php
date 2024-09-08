<?php

namespace App\View\Components\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Title extends Component
{
    public $addRoute;
    public $name;
    public $icon;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $icon, $addRoute = null)
    {
        $this->name = $name;
        $this->addRoute = $addRoute;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar.title');
    }
}
