<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Toont de registratiepagina voor bezoekers.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Registreert een nieuwe bezoeker.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Controleer de ingevulde gegevens.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ], [
            'name.required' => 'Vul je naam in.',
            'email.required' => 'Vul je e-mailadres in.',
            'email.email' => 'Vul een geldig e-mailadres in.',
            'email.unique' => 'Dit e-mailadres is al geregistreerd.',
            'password.required' => 'Vul een wachtwoord in.',
            'password.min' => 'Het wachtwoord moet minimaal 8 tekens bevatten.',
            'password.confirmed' => 'De wachtwoorden komen niet overeen.',
        ]);


        // Nieuwe gebruiker aanmaken.
        // Een gebruiker die zichzelf registreert is altijd een bezoeker.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'bezoeker',
        ]);

        event(new Registered($user));

        // De nieuwe bezoeker direct inloggen.
        Auth::login($user);
        
        // Na succesvolle registratie naar Home met een succesmelding.
        return redirect()
            ->route('home')
            ->with('success', 'Je account is succesvol aangemaakt! Welkom bij Sneakerness®.');
    }
}
