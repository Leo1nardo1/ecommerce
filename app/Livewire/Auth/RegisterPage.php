<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;

#[Title('Register - Magali')]
class RegisterPage extends Component
{
    // These hold the value from wire:model with it's respective name
    public $name;
    public $email;
    public $password;

    // register user

    public function save(){
        // Checks if the data typed follows the correct format and defines the format of the data. Allowing the user to return an error if not.
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|max:255',
        ]);


        // save to database
        // This saves the validated info to the database, assigning the value to the 'name', 'email', and 'password'. It's also where the password is hashed.
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        //This is what the route middleware will use to determine if the user is authenticated or not.
        auth()->login($user);

        // redirect to home page

        // intended is from laravel and it's used to redirect the user to the page they were trying to access before they were redirected to the login page.
        return redirect()->intended();
    }

    

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
