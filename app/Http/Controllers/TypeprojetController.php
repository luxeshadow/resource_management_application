<?php

namespace App\Http\Controllers;

use App\Models\Typeprojet;
use App\Http\Requests\StoreTypeprojetRequest;
use App\Http\Requests\UpdateTypeprojetRequest;

class TypeprojetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $typeProjets = TypeProjet::whereNull('deletetypeprojet')
        ->get(['id', 'nametypeprojet']);

// Retourner les données sous forme de JSON
          
         return response()->json($typeProjets);
}

public function listetypeprojet()
{
    try {
        // Récupérer tous les types de projet
        $typeprojets = TypeProjet::select('id', 'nametypeprojet', 'description')
            ->whereNull('deletetypeprojet')
            ->get();

        return response()->json(['data' => $typeprojets], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération des types de projet', 'details' => $e->getMessage()], 500);
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('typeProject');
    }

    /**
     * Store a newly created resource in storage.
     */

        //
        public function store(StoreTypeprojetRequest $request)
{
    try {
        // Vérifier si le type de projet existe déjà
        $existingTypeprojet = Typeprojet::where('nametypeprojet', $request->nametypeprojet)->first();
    
        if ($existingTypeprojet) {
            // Vérifier si la colonne deletetypeprojet est null
            if ($existingTypeprojet->deletetypeprojet !== null) {
                // Mettre la colonne deletetypeprojet à null
                $existingTypeprojet->deletetypeprojet = null;
                $existingTypeprojet->save();
                return response()->json(['message' => 'Type de projet ajouté avec succès'], 200);
            } else {
                // Le type de projet existe déjà avec deletetypeprojet null
                return response()->json(['error' => 'Le type de projet existe déjà'], 409);
            }
        } else {
            // Créer un nouveau type de projet
            $typeprojet = Typeprojet::create([
                'nametypeprojet' => $request->nametypeprojet,
                'description' => $request->description,
            ]);
    
            return response()->json(['message' => 'Nouveau type de projet créé', 'typeprojet' => $typeprojet], 201);
        }
    } catch (\Exception $e) {
        // Gérer l'exception et retourner un message d'erreur
        return response()->json(['error' => 'Erreur lors de la création du nouveau type de projet', 'details' => $e->getMessage()], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Typeprojet $typeprojet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Typeprojet $typeprojet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeprojetRequest $request, Typeprojet $typeprojet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   
    public function destroy(Typeprojet $typeprojet)
{
    try {
        // Ajouter 1 à la colonne deletetypeprojet
        $typeprojet->deletetypeprojet += 1;
        $typeprojet->save();
        
        return response()->json(['message' => 'Type de projet marqué comme supprimé avec succès'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la mise à jour du type de projet', 'details' => $e->getMessage()], 500);
    }
}

}
