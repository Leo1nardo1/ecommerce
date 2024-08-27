<?php

namespace App\Livewire;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Mail\OrderPlaced;
use Stripe\Checkout\Session;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use function PHPSTORM_META\map;
use Illuminate\Support\Facades\Mail;

#[Title('Checkout - Magali')]
class CheckoutPage extends Component
{
    //holds info typed by the user through form submission
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    // If user has no orders, he shouldn't access checkout page, this redirects him to products page. It's used on mount because mount method works at the page initialization
    public function mount(){
        $cart_items = CartManagement::getCartItemsFromCookie();
        if(count($cart_items)==0){
            return redirect('/products');
        }
    }
    
    public function placeOrder(){

       // dd($this->payment_method);

       $this->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'street_address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip_code' => 'required',
        'payment_method' => 'required',
       ]);

       $cart_items = CartManagement::getCartItemsFromCookie();
       $line_items = [];
       // Iterates through every item in the cart
       foreach($cart_items as $item){
        // Converts each cart item into Stripe-compatible format and adds to line_items array.
        $line_items[] = [
            'price_data' => [
                'currency' => 'USD',
                'unit_amount' => $item['unit_amount'] * 100,
                'product_data' =>[
                    'name' => $item['name'],
                ],
                
            ],
            'quantity' => $item['quantity'],
        ];
       }

       // Create a new order/address and assigns values to each attribute in the Model order
       $order = new Order();
       $order->user_id = auth()->user()->id;
       $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
       $order->payment_method = $this->payment_method;
       $order->status_payment = 'pending';
       $order->status = 'new';
       $order->currency = 'USD';
       $order->shipping_amount = 0;
       $order->shipping_method = 'none';
       $order->notes = 'Order placed by '. auth()->user()->name;

       $address = new Address();
       $address->first_name = $this->first_name;
       $address->last_name = $this->last_name;
       $address->phone = $this->phone;
       $address->street_address = $this->street_address;
       $address->city = $this->city;
       $address->state = $this->state;
       $address->zip_code = $this->zip_code;

       $redirect_url = '';

       if($this->payment_method == 'stripe'){
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);
        $redirect_url = $sessionCheckout->url;

       } else {
        $redirect_url = route('success');
       }

       //saves the order and the address with the id of the order
       $order->save();
       $address->order_id = $order->id;
       $address->save();
       // order Model hasMany relationship
       $order->items()->createMany($cart_items);
       CartManagement::clearCartItems();
       // For the e-mail / you can add it later with laravel, $order will initialize the value in OrderPlaced construct method
       Mail::to(request()->user())->send(new OrderPlaced($order));
       return redirect($redirect_url);
    }
    

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
