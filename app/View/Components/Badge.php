<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    
    public $type;
 
    public $show,$message;
    /**
     * Create a new component instance.
     * @return void
     */
    public function __construct($type = null,$show = null, $message = null)
    {
        $this->type = $type;
        $this->show = $show;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.badge');
    }
}
