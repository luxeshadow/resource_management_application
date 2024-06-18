<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    //

    //* @return \Illuminate\View\View
    //*/
   public function showRegistrationForm()
   {
       return view('inscription');
   }

   /**
    * Gère le processus d'inscription des utilisateurs.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function register(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
    
        // Enregistrement de l'image de profil
        $photoPath = $request->file('photo')->store('profile_photos', 'public');
    
        // Créer un nouvel utilisateur
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'photo' => $photoPath,
        ]);
    
        // Rediriger l'utilisateur vers une page de succès ou de connexion
        return redirect()->route('accueil', ['id' => $user->id])->with('success', 'Your account has been created successfully! Please login.');
    }


    

 
}