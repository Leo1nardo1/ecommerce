<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\CartManagement;

class Navbar extends Component
{
    public $total_count = 0;
    
    // The mount method in a Livewire component is a lifecycle hook that runs once, immediately after the component is instantiated but before it renders for the first time. It's typically used to perform any initial setup or state initialization.
    public function mount(){
        $this->total_count = count(CartManagement::getCartItemsFromCookie());
    }
    //On sets the function below it as a custom event, this custom event can be called with dispatch() livewire method
    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
