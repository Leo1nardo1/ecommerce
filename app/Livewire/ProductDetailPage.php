<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;

#[Title('Product Detail - Magali')]

class ProductDetailPage extends Component
{
    use LivewireAlert;
    public $slug;
    public $quantity = 1;

    

    //This function repeats in products page because one is for the product detail page and the other is for the general product page
    public function addToCart($product_id){
        //dd($product_id); is used to check if the function is working and returning correct values.
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);
        
        //Updates cart count  to navbar
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        //sweetalert2 - livewire alert configuration when an item is successfully added to the cart
        $this->alert('success', 'Product added to the cart!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'timerProgressBar' => false,
           ]);
    }

    //Initializes the $slug
    public function mount($slug){
        $this->slug = $slug;
    }

    public function increaseQty(){
        $this->quantity++;
    }

    public function decreaseQty(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }
    
    public function render()
    {
        //fetches the product slug from the database (the slug is inserted in filament admin panel upon creating a product)
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
