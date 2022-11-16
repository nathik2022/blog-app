<?php

namespace App\View\Components;

use Illuminate\View\Component;

class card extends Component
{

    public $title,$subTitle,$items;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title = null, $subTitle = null, $items = null)
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->items = $items;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
