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
        $brands = Brand::where('is_active', 1)->take(4)->get();
        $categories = Category::where('is_active', 1)->get();
        
        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
