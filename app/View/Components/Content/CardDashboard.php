<?php

namespace App\View\Components\Content;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardDashboard extends Component
{
    public $title;
    public $bgColor;
    public $value;
    public $icon;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $bgColor, $value, $icon)
    {
        $this->title = $title;
        $this->bgColor = $bgColor;
        $this->value = $value;
        $this->icon = $icon;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content.card-dashboard');
    }
}
