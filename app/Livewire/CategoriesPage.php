<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;

#[Title('Categories - Magali')]
class CategoriesPage extends Component
{
    public function render()
    {
        //Fetches categories in the model that have 'is_active' set to true (1) with get()
        $categories = Category::where('is_active', 1)->get();
        //returns the variable in an associative array so we can use it in a foreach in the categories-page blade file
        return view('livewire.categories-page', [
            'categories' => $categories,
        ]);
    }
}
