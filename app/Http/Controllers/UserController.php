<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   /* public function index()
    {
        return view ('users_register');
    }*/
   public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    /**
     * showing the login Page to the User
     */
    public function showLoginForm()


    {

        if (Auth::check()) {
            return redirect()->route('accueil'); // Rediriger vers la page d'accueil si l'utilisateur est déjà authentifié
        }

        return view('connexion');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/Home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function create()
    {
        return view('users_register');
    }

    /*
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('Accueil');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }*/

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('connexion');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        // Store the profile photo
        $photoPath = $request->file('photo')->store('profile_photos', 'public');

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'photo' => $photoPath,
        ]);

        dd('ok!!');

        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
