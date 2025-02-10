<?php

namespace Custom\Sales\Livewire;

use Livewire\Component;

class SalesIndex extends Component
{
    public $message = 'Hello from Sales Package!';

    public function render()
    {
        return view('sales::livewire.sales-index');
    }
}
