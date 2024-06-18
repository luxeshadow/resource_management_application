<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Http\Requests\StoreProjetRequest;
use App\Http\Requests\UpdateProjetRequest;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projets = Projet::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $projets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjetRequest $request)
    {
        try {
            
    
            // Créer un nouveau projet
            $projet = Projet::create([
                'nomprojet' => $request->nomprojet,
                'typeprojet' => $request->typeprojet,
                'description' => $request->description,
                'nomclient' => $request->nomclient,
                'telephone' => $request->telephone,
                'email' => $request->email,
            ]);
    
            return response()->json(['message' => 'Projet créé avec succès', 'employee' => $projet], 201);
        } catch (\Exception $e) {
            // Gérer l'exception et retourner un message d'erreur
            return response()->json(['error' => 'Erreur lors de la création du projet', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Projet $projet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projet $projet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjetRequest $request, Projet $projet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projet $projet)
    {
        //
    }

    public function getProjetsEnCoursOuNuls() {
        $projets = Projet::whereNull('status')->get();
        return response()->json($projets);
    }
}
