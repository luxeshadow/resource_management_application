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
        $competences = Competence::select('id', 'namecompetence')
            ->whereNull('deletecompetence')
            ->get();

        return response()->json($competences);
    }

    public function listecompetence()
    {
        try {
            // Récupérer toutes les compétences
            $competences = Competence::all();
    
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


            // Créer un nouveau projet
            $competence = Competence::create([
                'namecompetence' => $request->namecompetence,
                'description' => $request->description,

            ]);

            return response()->json(['message' => 'nouvelle competence cree', 'employee' => $competence], 201);
        } catch (\Exception $e) {
            // Gérer l'exception et retourner un message d'erreur
            return response()->json(['error' => 'Erreur lors de la création de la nouvelle competence', 'details' => $e->getMessage()], 500);
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
            // Supprimer la compétence
            $competence->delete();
            return response()->json(['message' => 'Compétence supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de la compétence', 'details' => $e->getMessage()], 500);
        }
    }    
}
