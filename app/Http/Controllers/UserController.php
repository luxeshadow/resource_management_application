<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;




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
            'password' => 'required|max:10',
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
            'telephone' => $validatedData['telephone'], // Assurez-vous que "telephone" est bien défini dans les données validées
            'password' => Hash::make($validatedData['password']),
            'photo' => $photoPath,
        ]);
    
        return response()->json(['message' => 'Employé ajouté avec succès'], 200);
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
        try {
            $validatedData = $request->validated();
    
            // Assurez-vous que toutes les clés nécessaires existent dans $validatedData
            $user->name = $validatedData['name'] ?? $user->name; // Utilisation de la valeur actuelle si non définie
            $user->email = $validatedData['email'] ?? $user->email;
            $user->telephone = $validatedData['telephone'] ?? $user->telephone;
    
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
    
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
                $user->photo = $photoPath;
            }
    
            $user->save();
            $user->refresh();
            $request->session()->put('user', $user);
    
            return response()->json(['message' => 'Profil utilisateur mis à jour avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du profil utilisateur', ['exception' => $e]);
            return response()->json(['error' => 'Erreur lors de la mise à jour du profil utilisateur'], 500);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
