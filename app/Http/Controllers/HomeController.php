<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    //
    public function CreateEmployer(){
        return view('Ins_Employer');
    }
    public function showprofile(){
        return view('profile');
    }
        //return response()->json(['error' => 'Photo invalide ou manquante'], 400);
        public function createUsers(){
            return view('users_register');
        }
    
    public function CreateProjet(){
        return view('Ins_projet');
    }
    //
    public function Accueil(){
        return view('KofDashboard');
    }
    public function Equipe(){
        return view('Equipe');
    }
    public function EquipeProjet(){
        return view('Equipe&Projet');
    }

    public function Historique(){
        return view('Historique');
    }
  
}
