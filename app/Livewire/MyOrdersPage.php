<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Magali - My Orders')]
class MyOrdersPage extends Component
{
    //write this when you're using method paginate
    use WithPagination;
    public function render()
    {
        $my_orders = Order::where('user_id', auth()->id())->latest()->paginate(5);
        return view('livewire.my-orders-page', [
            'orders' => $my_orders,
        ]);
    }
}
