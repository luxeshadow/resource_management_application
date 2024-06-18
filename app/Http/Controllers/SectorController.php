<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
        $sectors = Sector::select('id', 'namesector')
                 ->whereNull('deletesector')
                 ->get();

        return response()->json($sectors);
         // Placez-le ici pour vérifier les données avant de les renvoyer en JSON
        
     
     
    }

    public function listesector()
    {
        try {
            // Récupérer toutes les secteurs
            $sectors = Sector::all();
    
            return response()->json(['data' => $sectors], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des secteurs', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('addSectors');
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectorRequest $request)
    {
        try {
            // Créer un nouveau secteur
            $secteur = Sector::create([
                'namesector' => $request->namesector,
                'description' => $request->description,
            ]);
    
            return response()->json(['message' => 'Nouveau secteur créé avec succès', 'sector' => $secteur], 201);
        } catch (\Exception $e) {
            // Gérer l'exception et retourner un message d'erreur
            return response()->json(['error' => 'Erreur lors de la création du nouveau secteur', 'details' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Sector $sector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sector $sector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectorRequest $request, Sector $sector)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sector $sector)
    {
        //
        try {
            // Supprimer le secteur
            $sector->delete();
            return response()->json(['message' => 'Secteur supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du secteur', 'details' => $e->getMessage()], 500);
        }
    }
}
