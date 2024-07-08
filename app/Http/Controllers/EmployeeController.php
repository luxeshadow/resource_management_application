<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Typeprojet;
use App\Models\Assignation;
use App\Models\Task;
use App\Models\Specialist;
use App\Models\Sector;
use App\Models\Competence;
use Carbon\Carbon;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Projet;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['tasks.sector'])
            ->whereNull('deletemployee')
            ->orderBy('created_at', 'desc')
            ->get();

        // Préparez les données pour la réponse JSON
        $employeesData = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'nom' => $employee->nom,
                'telephone' => $employee->telephone,
                'email' => $employee->email,
                'profile' => $employee->profile,
                'secteurs' => $employee->tasks->pluck('sector.namesector')->toArray(),
            ];
        });

        return response()->json(['data' => $employeesData]);
    }


    /**
     * Show the form for creating a new resource.
     */
    // public function show(Employee $employee)
    // {
    //     // Récupérer les secteurs associés à cet employé
    //     $secteurs = $employee->sectors()->pluck('namesector')->toArray();

    //     // Récupérer les compétences associées à cet employé
    //     $competences = $employee->competences()->pluck('namecompetence')->toArray();

    //     // Fusionner les données de l'employé avec les secteurs et les compétences
    //     $data = $employee->toArray();
    //     $data['secteurs'] = $secteurs;
    //     $data['competences'] = $competences;

    //     // Retourner les détails de l'employé avec les secteurs et les compétences associés
    //     return response()->json($data);
    // }


    public function show(Employee $employee)
    {
        // Charger les secteurs associés à cet employé


        // Charger l'employé avec ses secteurs associés
        $employee = $employee->load('tasks.sector', 'specialists.competence');
        $totalProjectsAssigned = Assignation::where('employe_id', $employee->id)->count();

        // Préparer les données de l'employé pour la réponse JSON
        $employeeData = [
            'id' => $employee->id,
            'nom' => $employee->nom,
            'telephone' => $employee->telephone,
            'email' => $employee->email,
            'profile' => $employee->profile,
            'secteurs' => $employee->tasks->pluck('sector.namesector')->toArray(),
            'competences' => $employee->specialists->pluck('competence.namecompetence')->toArray(),
            'total_projects_assigned' => $totalProjectsAssigned,
        ];

        return response()->json($employeeData);
    }






    public function create()
    {
        return view('Ins_Employer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            // Récupérer le fichier de profil
            $profile = $request->file('profile');
            $nomficherprofile = $profile->getClientOriginalName();

            // Stocker le fichier dans le répertoire public/img
            $profile->storeAs('public/img', $nomficherprofile);

            // Créer un nouvel employé
            $employee = Employee::create([
                'nom' => $request->nom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'profile' => $nomficherprofile,
            ]);

            // Ajouter les secteurs associés à l'employé dans la table associative
            $secteurs = explode(',', $request->selectedSecteursIds);
            foreach ($secteurs as $secteurId) {
                Task::create([
                    'employe_id' => $employee->id,
                    'sector_id' => $secteurId,
                ]);
            }

            // Ajouter les compétences associées à l'employé dans la table associative
            $competences = explode(',', $request->selectedCompetencesIds);
            foreach ($competences as $competenceId) {
                Specialist::create([
                    'employe_id' => $employee->id,
                    'competence_id' => $competenceId,
                ]);
            }

            return response()->json(['message' => 'Employé créé avec succès', 'employee' => $employee], 201);
        } catch (\Exception $e) {
            // Gérer l'exception et retourner un message d'erreur
            return response()->json(['error' => 'Erreur lors de la création de l\'employé', 'details' => $e->getMessage()], 500);
        }
    }

    public function edit(Employee $employee)
    {
        return response()->json($employee);
    }
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            // Mettre à jour les données de base de l'employé
            $employee->update($request->except(['selectedSecteursIds', 'selectedCompetencesIds']));

            // Mettre à jour les secteurs associés à l'employé dans la table associative
            $secteurs = $request->selectedSecteursIds ? explode(',', $request->selectedSecteursIds) : [];
            $employee->sectors()->sync($secteurs);

            // Mettre à jour les compétences associées à l'employé dans la table associative
            $competences = $request->selectedCompetencesIds ? explode(',', $request->selectedCompetencesIds) : [];
            $employee->competences()->sync($competences);

            return response()->json(['message' => 'Employé mis à jour avec succès']);
        } catch (\Exception $e) {
            // Journaliser l'erreur pour un diagnostic ultérieur
            Log::error('Erreur lors de la mise à jour de l\'employé: ' . $e->getMessage());

            // Retourner une réponse JSON avec un message d'erreur
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'employé', 'details' => $e->getMessage()], 500);
        }
    }




    public function destroy(Employee $employee)
    {
        if (is_null($employee->disponibilite)) {
            $employee->deletemployee += 1;
            $employee->save();
            return response()->json(['message' => 'Le statut de suppression de l\'employé a été éffectué avec succès']);
        } else {
            return response()->json(['message' => 'L\'employé ne peut pas être supprimé car il est assigné à un projet actuellement'], 500);
        }
    }



    //////
    public function getDashboardData()
    {
        // Compétences
        $competences = Competence::pluck('namecompetence', 'id');
        $competenceData = [];

        foreach ($competences as $competenceId => $competenceNom) {
            $competenceData[$competenceNom] = Specialist::where('competence_id', $competenceId)
                ->join('employees', 'specialists.employe_id', '=', 'employees.id')
                ->whereNull('employees.deletemployee') // S'assurer que les employés supprimés sont pris en compte
                ->count();
        }


        // Secteurs
        $secteurs = Sector::pluck('namesector', 'id');
        $secteurData = [];

        foreach ($secteurs as $secteurId => $secteurNom) {
            $secteurData[$secteurNom] = Task::where('sector_id', $secteurId)
                ->join('employees', 'tasks.employe_id', '=', 'employees.id')
                ->whereNull('employees.deletemployee') // S'assurer que seuls les employés non supprimés sont pris en compte
                ->count();
        }

        $totalEmployees = Employee::whereNull('deletemployee')->count();
        $totalProjets = Projet::whereNull('deletprojet')->count();


        $typesProjets = TypeProjet::all();

        $projectsByMonth = [];

        foreach ($typesProjets as $typeProjet) {
            $projectsByMonth[$typeProjet->nametypeprojet] = Projet::where('typeprojet', $typeProjet->id)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as total')
                ->groupBy('month')
                ->get()
                ->pluck('total', 'month');
        }

        // Création d'un tableau de mois pour l'affichage sur l'axe x du graphique
        $months = collect(range(1, 12))->map(function ($month) {
            return now()->startOfYear()->addMonths($month - 1)->format('Y-m');
        });

        // Création des séries de données pour le graphique
        $projectsData = [];

        foreach ($typesProjets as $typeProjet) {
            $projectsData[$typeProjet->nametypeprojet] = $months->map(function ($month) use ($projectsByMonth, $typeProjet) {
                return $projectsByMonth[$typeProjet->nametypeprojet]->get($month, 0);
            });
        }


        return view('accueil', compact('competenceData', 'secteurData', 'totalEmployees', 'totalProjets', 'projectsData', 'months'));
    }

    public function getEmployeesWithNullDisponibilite()
    {
        $employees = Employee::with(['tasks.sector', 'specialists.competence'])
            ->whereNull('disponibilite')
            ->whereNull('deletemployee')
            ->get();

        $employees = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'nom' => $employee->nom,
                'secteurs' => $employee->tasks->pluck('sector.namesector')->toArray(),
                'competences' => $employee->specialists->pluck('competence.namecompetence')->toArray(),
                'profile' => asset('storage/img/' . $employee->profile),
                'telephone' => $employee->telephone,
                'email' => $employee->email,
            ];
        });

        return response()->json($employees);
    }
}
