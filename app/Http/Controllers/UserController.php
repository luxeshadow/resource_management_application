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
    // effacer le cache
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $response = $next($request);
            return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        });
    }
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

            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Stocker les informations de l'utilisateur dans la session
            $request->session()->put('user', [
                'name' => $user->name,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'photo' => $user->photo, // Assurez-vous que l'utilisateur a un champ 'photo'
            ]);
           
            // Rediriger vers la page d'accueil
            return redirect()->intended('/Home');
        }

        return back()->withErrors([
            'authentication' => 'L\'adresse e-mail ou le mot de passe est incorrect.',
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
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // Valider les données reçues via le formulaire
        $validatedData = $request->validated();

        // Mettre à jour les attributs de l'utilisateur
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->telephone = $validatedData['telephone'];

        // Si un nouveau mot de passe est fourni, le hasher et le sauvegarder
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Si une nouvelle photo est fournie, la sauvegarder
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        // Sauvegarder les modifications
        $user->save();

        // Retourner une réponse JSON avec un message de succès
        return response()->json(['message' => 'Profil utilisateur mis à jour avec succès']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
