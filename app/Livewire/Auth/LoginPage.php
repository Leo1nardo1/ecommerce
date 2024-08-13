<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login - Magali')]
class LoginPage extends Component
{
    // These hold the value from wire:model with it's respective name
    public $email;
    public $password;

    // validates the data typed by the user, stored in the public variables. Also handles the error that will be shown with @error @enderror in the blade file
    public function save(){
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        // handles the customized error message when either the email or password is incorrect, however, if the email doesn't exist, the default @error @enderror messages will appear.
        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('error', 'E-mail or password are incorrect');
            return;
        } 

        return redirect()->intended();
    }


    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
