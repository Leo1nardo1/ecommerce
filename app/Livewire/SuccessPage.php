<?php

namespace App\Livewire;

use Stripe\Stripe;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Url;
use Stripe\Checkout\Session;
use Livewire\Attributes\Title;

#[Title('Success - Magali')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
         // Order model has hasOne relationship with address. Gets the latest order created by the current logged in user.
        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();
        if($this->session_id){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session_info = Session::retrieve($this->session_id);

            if($session_info->status_payment != 'paid'){
                $latest_order->status_payment = 'failed';
                $latest_order->save();
                return redirect()->route('cancel');

            }else if($session_info->payment_status == 'paid'){
                $latest_order->status_payment = 'paid';
                $latest_order->save();
            }
        }

        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
