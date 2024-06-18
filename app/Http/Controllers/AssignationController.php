<?php

namespace App\Http\Controllers;

use App\Models\Assignation;
use App\Models\Employee;
use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreAssignationRequest;
use App\Http\Requests\UpdateAssignationRequest;
use Psy\Readline\Hoa\Console;

class AssignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les assignations avec les relations projet et employé
        $projets = Projet::with(['assignations.employe'])
            ->where('status', 'En cours')
            ->get();
    
        // Récupérer tous les employés avec leurs tâches, secteurs, compétences, et projets assignés
        $employees = Employee::with(['tasks.sector', 'specialists.competence', 'assignations.projet'])
            ->get();
    
        // Passer les données récupérées à la vue
        return view('Equipe&Projet', compact('projets', 'employees'));
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
    public function store(StoreAssignationRequest $request)
    {
        try {

            // Récupérer les données validées de la requête
            $data = $request->all();

            // Récupérer le projet


            try {
                $projet = Projet::findOrFail($data['projet_id']);
            } catch (ModelNotFoundException $e) {
                // Gérer le cas où le projet n'est pas trouvé
                return response()->json(['message' => 'Projet non trouvé.'], 404);
            }



            // Ajouter les employés au projet pour la date donnée et mettre à jour leur disponibilité
            foreach ($data['employe_id'] as $employeeId) {
                $employee = Employee::findOrFail($employeeId);

                // Assigner l'employé au projet pour la date donnée
                Assignation::create([
                    'projet_id' => $projet->id,
                    'employe_id' => $employee->id,
                    'date_fin' => $data['date_fin'],
                ]);

                if (Schema::hasColumn('employees', 'disponibilite')) {
                    Log::info('Avant mise à jour disponibilité', ['employee_id' => $employee->id, 'disponibilite' => $employee->disponibilite]);
                    $employee->update(['disponibilite' => 1]);
                    Log::info('Après mise à jour disponibilité', ['employee_id' => $employee->id, 'disponibilite' => $employee->fresh()->disponibilite]);
                } else {
                    Log::warning('Colonne "disponibilite" non trouvée dans la table employees');
                }
            }

            $projet->status = 'En cours';
            $projet->save();

            return response()->json(['message' => 'Équipe créée avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création de l\'équipe.'], 500);
        }
    }


    public function addmember(StoreAssignationRequest $request)
    {
        try {

            // Récupérer les données validées de la requête
            $data = $request->all();

            // Récupérer le projet


            try {
                $projet = Projet::findOrFail($data['projet_id']);
            } catch (ModelNotFoundException $e) {
                // Gérer le cas où le projet n'est pas trouvé
                return response()->json(['message' => 'Projet non trouvé.'], 404);
            }



            // Ajouter les employés au projet pour la date donnée et mettre à jour leur disponibilité
            foreach ($data['employe_id'] as $employeeId) {
                $employee = Employee::findOrFail($employeeId);

                // Assigner l'employé au projet pour la date donnée
                Assignation::create([
                    'projet_id' => $projet->id,
                    'employe_id' => $employee->id,
                    //'date_fin' => $data['date_fin'],
                ]);

                if (Schema::hasColumn('employees', 'disponibilite')) {
                    Log::info('Avant mise à jour disponibilité', ['employee_id' => $employee->id, 'disponibilite' => $employee->disponibilite]);
                    $employee->update(['disponibilite' => 1]);
                    Log::info('Après mise à jour disponibilité', ['employee_id' => $employee->id, 'disponibilite' => $employee->fresh()->disponibilite]);
                } else {
                    Log::warning('Colonne "disponibilite" non trouvée dans la table employees');
                }
            }

            $projet->status = 'En cours';
            $projet->save();

            return response()->json(['message' => 'Équipe créée avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la création de l\'équipe.'], 500);
        }
    }







    /**
     * Display the specified resource.
     */
    public function show(Assignation $assignation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignation $assignation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignationRequest $request, Assignation $assignation)
    {//

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignation $assignation)
    {
        $employe = $assignation->employe;

        // Supprimer l'assignation
        $assignation->delete();

        // Mettre à jour la disponibilité de l'employé
        $employe->disponibilite = null;
        $employe->save();

        return response()->json(['success' => true]);
    }

   

// Méthode pour compter les assignations d'aujourd'hui
   
   
public function archive($id)
{
    $projet = Projet::with('assignations.employe')->findOrFail($id);

    // Modifier le statut du projet
    $projet->status = 'Terminé';
    $projet->save();

    // Mettre à jour la disponibilité des employés
    foreach ($projet->assignations as $assignation) {
        $employe = $assignation->employe;
        if ($employe) {
            $employe->disponibilite = null;
            $employe->save();
        }
    }

    return response()->json(['success' => true]);
}

public function historique()
{
    // Charger les projets avec leurs assignations et les relations employé, secteurs et compétences
    $projets = Projet::with(['assignations.employe.sectors', 'assignations.employe.competences'])
        ->where('status', 'Terminé')
        ->get();

    // Charger les employés avec leurs relations de secteurs, compétences et assignations
    $employees = Employee::with(['sectors', 'competences', 'assignations.projet'])
        ->get();

    // Retourner la vue avec les projets et les employés
    return view('Historique', compact('projets', 'employees'));
}



public function getAssignmentsDueToday()
{
    $today = Carbon::today();
    $count = Assignation::whereDate('date_fin','<=', $today)
    ->whereHas('projet', function ($query) {
        $query->where('status', 'En cours');
    })
    ->count();

  return response()->json(['count' => $count]);
}
   
    
}
