<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    public $routeActive;
    public $routeLink;
    public $routeStore;

    /**
     * Create a new component instance.
     */
    public function __construct($routeActive = null, $routeLink = null, $routeStore = null)
    {
        $this->routeActive = $routeActive;
        $this->routeLink = $routeLink;
        $this->routeStore = $routeStore;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}
