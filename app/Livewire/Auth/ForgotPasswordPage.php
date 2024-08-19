<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Password;

#[Title('Forgot Password - Magali')]
class ForgotPasswordPage extends Component
{
    //This variable stores the email from input tag through the call of the wire:model="email" attribute
    public $email;
    
    public function save(){
        //Validation to make sure the email entered is abiding by the rules. exists:user,email checks if it already exists
        $this->validate([
            'email' => 'required|email|exists:users,email|max:255',
        ]);
        //This is for mailtrap
        $status = Password::sendResetLink(['email' => $this->email ]);

        if($status === Password::RESET_LINK_SENT){
            session()->flash('success', 'Password reset link has been sent to your e-mail');
            $this->email= '';
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
