@extends('KofDashboard')

@section('projet')

<link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.js') }}"></script>

<style>
    h1:hover {
        letter-spacing: 5px;
        cursor: pointer;
    }
</style>

<h1 class="main-title" style="margin-top: 10px; font-size: 22px; margin:5px">{{ __('messages.adpro') }}</h1><br>
<div class="row col-xl-12 ">
    <main class="container col-xl-4">
        <form id="ajouterProjet" class="sign-up-form form container" action="" method="POST">
            @csrf
            <label class="form-label-wrapper">
                <p class="form-label">Nom Projet</p>
                <input class="form-input" name="nomprojet" type="text" placeholder="Entrer le nom du projet..." >
                <span class="error-message" style="color: red;"></span>
            </label>
           
            <label class="form-label-wrapper">
                <p class="form-label">Type de Projet</p>
                <select name="typeprojet" class="form-input" title="Open list">
                    <option value="" disabled selected hidden></option>
                    <option value="Web">Web</option>
                    <option value="Mobile">Mobile</option>
                    <option value="Datascience">Datascience</option>
                </select>
                <div style="color: red;" class="error-message"></div>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Description</p>
                <textarea class="form-input" name="description" cols="30" placeholder="Description du projet..." rows="10"></textarea>
                <span class="error-message" style="color: red;"></span>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Nom du Client</p>
                <input class="form-input" name="nomclient" placeholder="Entrer le nom du client..." >
                <span class="error-message" style="color: red;"></span>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Telephone</p>
                <input class="form-input" name="telephone" placeholder="Enter le telephone du client..." >
                <span class="error-message" style="color: red;"></span>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Email</p>
                <input class="form-input" name="email" type="email" placeholder="Entrer l'email...">
                <span class="error-message" style="color: red;"></span>
            </label>
            <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Ajouter Projet</button>
        </form>
        <br>
    </main>
    

    <main class="col-xl-8">
        <div class="users-table sign-up-form form container" >
            <div class="main-nav-start">
                <div class="search-wrapper">
                    <i data-feather="search" aria-hidden="true"></i>
                    <input type="text" placeholder="Enter keywords ..." required>
                </div>
            </div>
                <br>
             <div class="users-table table-wrapper">

                
                <table class="posts-table">
                    <thead>
                        <tr class="users-table-info">
                            <th>Nom Projet</th>
                            <th>Type Projet</th>
                            <th>Nom du Client</th>
                            <th>Telephone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="projets-list" ></tbody>
                </table>
            </div>
        </div>
        <div class="pagination-wrapper">

        </div>
        <br>
    </main>
</div>

<script>
$(document).ready(function() {

    $('#ajouterProjet').on('submit', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut du formulaire
        console.log('Form submitted');

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route('projets.store') }}', // URL de la route définie dans Laravel
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Success:', response);
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Projet enregistré avec succès",
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#ajouterProjet')[0].reset();
                loadProjets(); // Recharger les Projets après ajout
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    // Afficher les erreurs dans le formulaire
                    Object.keys(errors).forEach(function(key) {
                        var errorMessage = errors[key][0]; // Prendre le premier message d'erreur
                        $('[name="' + key + '"]').siblings('.error-message').html(errorMessage);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Une erreur est survenue"
                    });
                }
            }
        });
    });
    $('.form-input').on('input', function() {
                    $(this).siblings('.error-message').html('');
    });

    loadProjets();

// AFFICHER LES PROJETS

let allProjets = [];
let currentPage = 1;
const itemsPerPage = 6;

function loadProjets() {
    $.ajax({
        url: '{{ route('projets.index') }}',
        type: 'GET',
        success: function(response) {
          allProjets = response.data;
            displayEmployers(allProjets.slice(0, itemsPerPage));
            setupPagination(allProjets.length);

        },
        error: function(xhr, status, error) {
            console.log('Erreur lors du chargement des projets:', error);
        }
    });
}

function displayEmployers(projets) {
    var projetsList = $('#projets-list');
    projetsList.empty();

    projets.forEach(function(projet) {
      
        var projetRow = `
<tr>
  <td>
  <div class="circle"></div>
  <div class="overflow-text">${projet.nomprojet}</div>
</td>
    
    <td>${projet.typeprojet}</td>
    <td>${projet.nomclient}</td>
    <td>${projet.telephone}</td>
    
    <td>
        <button title="Suprimer" class="btn-del" data-id="${projet.id}"><img src="img/sup.png" alt="" style="width:19px;"></button>
        <span class="sr-only">Suprimer</span>
        <button class="btn-edit" data-id="${projet.id}"><img src="img/edit.png" alt="" style="width: 19px;"></button>
        <button id="openModalBtn" class="btn-profile" data-id="${projet.id}"><img  src="img/view.png" alt="" style="width: 19px;"></button>
       
    </td>
</tr>
`;
        projetsList.append(projetRow);

    });
}

function setupPagination(totalItems) {
    var paginationWrapper = $('.pagination-wrapper');
    paginationWrapper.empty();
    var totalPages = Math.ceil(totalItems / itemsPerPage);

    var prevButton =
        `<button class="page-link" id="prev-page" ${currentPage === 1 ? 'disabled' : ''}>Previous...</button>`;
    paginationWrapper.append(prevButton);

    for (let i = 1; i <= totalPages; i++) {
        var pageButton = `
<button class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
`;
        paginationWrapper.append(pageButton);
    }

    var nextButton =
        `<button class="page-link" id="next-page" ${currentPage === totalPages ? 'disabled' : ''}>Next...</button>`;
    paginationWrapper.append(nextButton);
}

$(document).on('click', '.page-link', function() {
    var page = $(this).data('page');
    if (page) {
        currentPage = page;
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        displayEmployers(allProjets.slice(startIndex, endIndex));
        setupPagination(allProjets.length);
    }
});

$(document).on('click', '#prev-page', function() {
    if (currentPage > 1) {
        currentPage--;
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        displayEmployers(allProjets.slice(startIndex, endIndex));
        setupPagination(allProjets.length);
    }
});

$(document).on('click', '#next-page', function() {
    var totalPages = Math.ceil(allProjets.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        displayEmployers(allProjets.slice(startIndex, endIndex));
        setupPagination(allProjets.length);
    }
});

});
</script>
@endsection
