<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Login')]
/**
 * Class Login
 *
 * This class handles the login functionality of the application.
 *
 * @package App\Livewire
 */
/**
 * @property string $email The email address of the user.
 * @property string $password The password of the user.
 * @property bool $remember Indicates whether the user wants to be remembered.
 */
class Login extends Component
{
    public function render()
    {
        return view('livewire.login');
    }
}
