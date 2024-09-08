<?php

namespace App\Livewire;

use Stripe\Stripe;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Url;
use Stripe\Checkout\Session;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

#[Title('Magali - Success')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
{
    $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();
    if ($this->session_id) {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session_info = Session::retrieve($this->session_id);

            Log::info('Stripe Session Info:', ['status' => $session_info->payment_status]);

            if ($session_info->payment_status !== 'paid') {
                $latest_order->status_payment = 'failed';
                $latest_order->save();
                Log::info('Redirecting to cancel route due to payment status failure');
                return redirect()->route('cancel');
            } else {
                $latest_order->status_payment = 'paid';
                $latest_order->save();
                Log::info('Payment status is paid, order updated');
            }
        } catch (\Exception $e) {
            Log::error('Stripe session retrieval error: ' . $e->getMessage());
            Log::info('Redirecting to cancel route due to exception');
            return redirect()->route('cancel');
        }
    }

    Log::info('Rendering success page');
    return view('livewire.success-page', [
        'order' => $latest_order,
    ]);
}

}
