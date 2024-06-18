<?php

use App\Http\Controllers\AssignationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//Route for the login page (Added By Other Person)
Route::get('/connexion', [UserController::class, 'showLoginForm'])->name('auth.login');
Route::post('/connexion', [UserController::class, 'login'])->name('auth.verification');
Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');

Route::resource('users', UserController::class);

//Your Owner Route to access to Connnexion Page
/*
Route::get('/connexion', function () {
    return view('Connexion');
})->name('connexion');
*/




Route::middleware('auth')->group(function () {
    Route::get('/Historique', [AssignationController::class, 'Historique'])->name('historique');
    Route::post('/addmember', [AssignationController::class, 'addmember'])->name('addmember');
    Route::get('/Employer', [HomeController::class, 'CreateEmployer'])->name('CreateEmployer');
    Route::get('/liste', [HomeController::class, 'listeEmployers'])->name('listeEmployers');
    Route::get('/Projet', [HomeController::class, 'CreateProjet'])->name('CreateProjet');
    Route::get('/Profile', [HomeController::class, 'showprofile'])->name('showprofile');
    Route::get('/Home', [EmployeeController::class, 'getDashboardData'])->name('accueil');
    
    
    
    // Route des Assignation
    Route::get('/Equipe', [HomeController::class, 'Equipe'])->name('Equipe');
    Route::get('/Equipe&Projet', [AssignationController::class, 'index'])->name('Equipe&Projet');
    Route::get('/projets/en-cours-ou-nuls', [ProjetController::class, 'getProjetsEnCoursOuNuls']);
    Route::get('employees/disponibilite/null', [EmployeeController::class, 'getEmployeesWithNullDisponibilite']);
    Route::get('/listecompetence', [CompetenceController::class, 'listecompetence']);
    Route::get('/listesector', [SectorController::class, 'listesector']);
    Route::get('/notification', [AssignationController::class, 'getAssignmentsDueToday'])->name('notification');
    // Ressource
    Route::resource('employees', EmployeeController::class);
    Route::resource('projets', ProjetController::class);
    Route::resource('assignations', AssignationController::class);
    Route::post('/projets/archive/{id}', [AssignationController::class, 'archive'])->name('projets.archive');
    
    Route::resource('sectors', SectorController::class);
    Route::resource('competences', CompetenceController::class);
});

/*
The Previous Delete Code is here
Route::middleware('auth')->group(function () {
    Route::get('/Historique', [AssignationController::class, 'Historique'])->name('historique');
    Route::post('/addmember', [AssignationController::class, 'addmember'])->name('addmember');
    Route::get('/Employer', [HomeController::class, 'CreateEmployer'])->name('CreateEmployer');
    Route::get('/liste', [HomeController::class, 'listeEmployers'])->name('listeEmployers');
    Route::get('/Projet', [HomeController::class, 'CreateProjet'])->name('CreateProjet');
    Route::get('/Profile', [HomeController::class, 'showprofile'])->name('showprofile');
    Route::get('/Users', [HomeController::class, 'createUsers'])->name('createUsers');
    Route::get('/Home', [EmployeeController::class, 'getDashboardData'])->name('accueil');
    
    // Route des Assignation
    Route::get('/Equipe', [HomeController::class, 'Equipe'])->name('Equipe');
    Route::get('/Equipe&Projet', [AssignationController::class, 'index'])->name('Equipe&Projet');
    Route::get('/projets/en-cours-ou-nuls', [ProjetController::class, 'getProjetsEnCoursOuNuls']);
    Route::get('employees/disponibilite/null', [EmployeeController::class, 'getEmployeesWithNullDisponibilite']);
    Route::get('/listecompetence', [CompetenceController::class, 'listecompetence']);
    Route::get('/listesector', [SectorController::class, 'listesector']);
    
    // Ressource
    Route::resource('employees', EmployeeController::class);
    Route::resource('projets', ProjetController::class);
    Route::resource('assignations', AssignationController::class);
    Route::post('/projets/archive/{id}', [AssignationController::class, 'archive'])->name('projets.archive');
    Route::resource('users', UserController::class);
    Route::resource('sectors', SectorController::class);
    Route::resource('competences', CompetenceController::class);
});
*/

//Langue

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr', 'uz'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

