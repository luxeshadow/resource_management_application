<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Http\Requests\StoreCompetenceRequest;
use App\Http\Requests\UpdateCompetenceRequest;

class CompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
        $competence = Competence::select('id', 'namecompetence')
                 ->whereNull('deletecompetence')
                 ->get();

        return response()->json($competence);
         // Placez-le ici pour vérifier les données avant de les renvoyer en JSON
        
     
     
    }
   

    public function listecompetence()
    {
        try {
            // Récupérer toutes les compétences
            $competences = Competence::select('id', 'namecompetence','description')
            ->whereNull('deletecompetence')
            ->get();
    
            return response()->json(['data' => $competences], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des compétences', 'details' => $e->getMessage()], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('addCompetences');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetenceRequest $request)
    {
        //
        try {
            // Vérifier si la compétence existe déjà
            $existingCompetence = Competence::where('namecompetence', $request->namecompetence)->first();
        
            if ($existingCompetence) {
                // Vérifier si la colonne deletecompetence est null
                if ($existingCompetence->deletecompetence !== null) {
                    // Mettre la colonne deletecompetence à null
                    $existingCompetence->deletecompetence = null;
                    $existingCompetence->save();
                    return response()->json(['message' => 'Compétence ajoutée avec succès'], 200);
                } else {
                    // La compétence existe déjà avec deletecompetence null
                    return response()->json(['error' => 'La compétence existe déjà'], 409);
                }
            } else {
                // Créer une nouvelle compétence
                $competence = Competence::create([
                    'namecompetence' => $request->namecompetence,
                    'description' => $request->description,
                ]);
        
                return response()->json(['message' => 'Nouvelle compétence créée', 'competence' => $competence], 201);
            }
        } catch (\Exception $e) {
            // Gérer l'exception et retourner un message d'erreur
            return response()->json(['error' => 'Erreur lors de la création de la nouvelle compétence', 'details' => $e->getMessage()], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Competence $competence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competence $competence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetenceRequest $request, Competence $competence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competence $competence)
    {
        
        try {
            // Ajouter 1 à la colonne deletecompetence
            $competence->deletecompetence += 1;
            $competence->save();
        
            return response()->json(['message' => 'Compétence marquée comme supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de la compétence', 'details' => $e->getMessage()], 500);
        }
        
    }    
}
