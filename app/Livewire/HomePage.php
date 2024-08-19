<?php

namespace App\Livewire;
use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

//Changes the tab title on the home page.
#[Title('Home Page - Magali')]

class HomePage extends Component
{
    public function render()
    {
        //This will show the brands. is_active checks the one's who are set as 1, take only takes 4 brands and get() method fetches them
        $brands = Brand::where('is_active', 1)->take(4)->get();
        $categories = Category::where('is_active', 1)->get();
        
        //this assigns the variables to these keys so we can use them in the home-page.blade
        //The keys will be used as variables in the foreach
        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
