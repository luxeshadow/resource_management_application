<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Http\Requests\StoreProjetRequest;
use App\Http\Requests\UpdateProjetRequest;
use Illuminate\Support\Facades\Log;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projets = Projet::with('typeprojet')
                            ->whereNull('deletprojet')
                            ->orderByDesc('created_at')
                            ->get();
    
            return response()->json(['data' => $projets]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des projets: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne du serveur lors du chargement des projets.'], 500);
        }
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
        $projet = $projet->load('tasks.sector', 'specialists.competence');
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projet $projet)
    {
        //
        return response()->json($projet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjetRequest $request, Projet $projet)
    {
        //
        try {
            // Mettre à jour les données de base du projet
            $projet->update($request->except(['typeprojet']));
    
            // Mettre à jour le type de projet associé
            if ($request->has('typeprojet')) {
                $projet->typeprojet = $request->typeprojet;
                $projet->save();
            }
    
            return response()->json(['message' => 'Projet mis à jour avec succès', 'projet' => $projet]);
        } catch (\Exception $e) {
            // Journaliser l'erreur pour un diagnostic ultérieur
            Log::error('Erreur lors de la mise à jour du projet: ' . $e->getMessage());
    
            // Retourner une réponse JSON avec un message d'erreur
            return response()->json(['error' => 'Erreur lors de la mise à jour du projet', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projet $projet)
    {
        //
        try {
            if (is_null($projet->status)) {
                // Supprimer le projet si le statut est nul
                $projet->delete();
                return response()->json(['message' => 'Projet supprimé avec succès'], 200);
            } elseif ($projet->status === 'En cours' || $projet->status === 'Terminé') {
                // Mettre à jour la colonne deletprojet à 1 si le statut est 'en cours' ou 'termine'
                $projet->deletprojet = 1;
                $projet->save();
                return response()->json(['message' => 'Proje supprimé avec succès'], 200);
            } else {
                return response()->json(['error' => 'Statut du projet non pris en charge'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du projet', 'details' => $e->getMessage()], 500);
        }
    }

    public function getProjetsEnCoursOuNuls() {
        $projets = Projet::whereNull('status')->get();
        return response()->json($projets);
    }
}
