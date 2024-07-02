@extends('KofDashboard')
@section('addCompetences')
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.js') }}"></script>

    <section>
        <div id="addCompetencesModal" class="modal">
            <div style="margin-top: 40px;" class="sign-up-form form container col-xl-6">
                <span style="font-size: 50px;font-weight:600; cursor: pointer;float: right; color: blue"
                    class="clo">&times;</span>
                <h2 class="main-title">{{ __('messages.adcomp') }}</h2>
                <form id="addCompetence">
                    @csrf
                    <label class="form-label-wrapper">
                        <p class="form-label">Competence</p>
                        <input style="text-transform: capitalize" name="namecompetence" class="form-input" placeholder=" Laravel..." autocomplete="off">
                        <div style="color: red;" class="error-message"></div>
                    </label>
                    <label class="form-label-wrapper">
                        <p class="form-label">Description</p>
                        <input name="description" class="form-input" placeholder="Framework php" autocomplete="off">
                        <div style="color: red;" class="error-message"></div>
                    </label>
                    <button type="submit"
                        class="form-btn primary-default-btn transparent-btn col-md-8">{{ __('messages.adcomp') }}</button>
                </form>
            </div>
        </div>
    </section>

    {{-- liste --}}

    <main style="margin: auto; margin-top:30px" class="col-xl-11">
        <h1 class="main-title" style="margin-top: 10px; font-size: 22px; margin:5px">{{ __('messages.adcomp') }}</h1><br>
        <div class="users-table sign-up-form form container">
            <div style="margin-right: 35px;" class="search-wrapper">
                <input type="text" placeholder="Recherche competence..." required>
            </div>
            <div class="users-table table-wrapper" id="listeEmployer">
                <table class="">
                    <thead class="stat-cards-info__num">
                        <tr class="users-table-info">
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="competences-list"></tbody>
                </table>
            </div>
            <button class="main-nav-end" id="addCompetencesButton" title="Cliquez pour ajouter"
                style="background: none;display: flex;">
                <div style="display: flex;">
                    <img src="{{ asset('img/add.png') }}" alt="" style="width:32px; height:32px;">
                    <p class="stat-cards-info__num" style="margin: 5px;font-weight: 500;font-size: 20px">
                        {{ __('messages.adcomp') }}
                    </p>
                </div>
            </button>
        </div>
        <div class="pagination-wrapper"></div>
        <br>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addMemberButton = document.getElementById('addCompetencesButton');
            var modal = document.getElementById('addCompetencesModal');
            var closeBtn = document.getElementsByClassName('clo')[0];

            addMemberButton.addEventListener('click', function() {
                modal.style.display = 'block';
            });

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });

        $(document).ready(function() {
    $('#addCompetence').on('submit', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut du formulaire
        console.log('Form submitted');

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route('competences.store') }}', // URL de la route définie dans Laravel
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Success:', response);
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Compétence enregistrée avec succès",
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#addCompetence')[0].reset();
                loadCompetences(); // Recharger les compétences après ajout
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    var errorMessages = '';
                    // Accumuler tous les messages d'erreur
                    Object.keys(errors).forEach(function(key) {
                        var errorMessage = errors[key][0]; // Prendre le premier message d'erreur
                        errorMessages += errorMessage + '<br>';
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: errorMessages // Utiliser HTML pour afficher plusieurs lignes
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Cette competence existe de"
                    });
                }
            }
        });
    });



            $('.form-input').on('input', function() {
                $(this).siblings('.error-message').html('');
            });
            //suprimer competence
            $(document).on('click', '.btn-del', function() {
                var competenceId = $(this).data('id');
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: 'Vous ne pourrez pas revenir en arrière!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('competences.destroy', '') }}/' + competenceId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Success:', response);
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Compétence supprimée avec succès",
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                // Recharger la liste des compétences après la suppression
                                loadCompetences();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error:', xhr.responseText);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Une erreur est survenue lors de la suppression de la compétence"
                                });
                            }
                        });
                    }
                });
            });


            loadCompetences();

            // AFFICHER LES COMPÉTENCES

            let allCompetences = [];
            let currentPage = 1;
            const itemsPerPage = 4;

            function loadCompetences() {
                $.ajax({
                    url: '/listecompetence',
                    type: 'GET',
                    success: function(response) {
                        allCompetences = response.data;
                        displayCompetences(allCompetences.slice(0, itemsPerPage));
                        setupPagination(allCompetences.length);
                    },
                    error: function(xhr, status, error) {
                        console.log('Erreur lors du chargement des compétences:', error);
                    }
                });
            }

            function displayCompetences(competences) {
                var competencesList = $('#competences-list');
                competencesList.empty();

                competences.forEach(function(competence) {
                    var competenceRow = `
                        <tr>
                            <td>
                               
                               ${competence.namecompetence}
                            </td>
                            <td>${competence.description}</td>
                            <td>
                                <button title="Supprimer" class="btn-del" data-id="${competence.id}"><img src="{{ asset('img/eye.png') }}" alt="" style="width:19px; height:19px;"></button>
                                <span class="sr-only">Supprimer</span>
                            </td>
                        </tr>
                    `;
                    competencesList.append(competenceRow);
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
                    var pageButton =
                        `<button class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
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
                    displayCompetences(allCompetences.slice(startIndex, endIndex));
                    setupPagination(allCompetences.length);
                }
            });

            $(document).on('click', '#prev-page', function() {
                if (currentPage > 1) {
                    currentPage--;
                    var startIndex = (currentPage - 1) * itemsPerPage;
                    var endIndex = startIndex + itemsPerPage;
                    displayCompetences(allCompetences.slice(startIndex, endIndex));
                    setupPagination(allCompetences.length);
                }
            });

            $(document).on('click', '#next-page', function() {
                var totalPages = Math.ceil(allCompetences.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    var startIndex = (currentPage - 1) * itemsPerPage;
                    var endIndex = startIndex + itemsPerPage;
                    displayCompetences(allCompetences.slice(startIndex, endIndex));
                    setupPagination(allCompetences.length);
                }
            });
            $('.search-wrapper input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                var filteredCompetences = allCompetences.filter(function(competence) {
                    return competence.namecompetence.toLowerCase().includes(searchTerm) ||
                        competence.description.toLowerCase().includes(searchTerm);
                });
                currentPage = 1; // Réinitialiser à la première page
                displayCompetences(filteredCompetences.slice(0, itemsPerPage));
                setupPagination(filteredCompetences.length);
            });


        });
    </script>
@endsection
