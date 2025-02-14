@extends('KofDashboard')

@section('equipe&projet')
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        h2:hover {
            letter-spacing: 5px;
            cursor: pointer;
        }
    </style>
    <br>
    <h2 title="titre" style="background: none;display: flex;margin-left: 30px">
        <img src="{{ asset('img/fille.png') }}" alt="" style="width:60px; height:60px;">
        <p class="stat-cards-info__num" style="margin-top: 10px;font-weight: 700;font-size: 25px">
            Projets et Suivit
        </p>
    </h2>


    @php
        $tousProjetsTermines = true;
        foreach ($projets as $projet) {
            if ($projet->status != 'Terminé') {
                $tousProjetsTermines = false;
                break;
            }
        }
    @endphp

    @if ($tousProjetsTermines)
        <div class="" style="text-align: center; font-weight: 600; cursor: pointer; margin-top: 100px">
            <img src="{{ asset('img/fold.png') }}" alt=""
                style="width: 200px; height: 200px; justify-content: center">
            <p class="stat-cards-info__num">Aucun projet en cours...</p>
        </div>
    @else
        @foreach ($projets as $projet)
            @if ($projet->status == 'En cours' && $projet->assignations->isNotEmpty())
                <main class="stat-cards-item" style="margin: 20px">
                    <input id="selectProjet" type="hidden" value="{{ $projet->id }}">
                    <input id="modalProjectId" type="hidden" value="">

                    <article style="margin-bottom: 20px" class="row">
                        <div class="col-xl-3" style="text-align: center; margin-top: 50px">
                            <div title="Nom du projet" class="stat-cards-info__num"
                                style="margin-left: 110px; margin-bottom: 30px; margin-top: 20px; font-weight: 600; text-align: center;">
                                <div class="circle"></div>
                                <div class="overflow-text">{{ $projet->nomprojet }}</div>
                            </div>

                            <br><br>
                            <p class="stat-cards-info__num">Date Début {{ $projet->created_at }}</p>
                            <style>
                                .expired {
                                    color: #f26464;
                                    text-shadow: 2px 2px 5px #f26464;
                                }
                            </style>
                            <p class="stat-cards-info__num">
                                <span class="date-fin">Date Fin {{ $projet->date_fin }}</span>
                            </p>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const dateElements = document.querySelectorAll('.date-fin');

                                    dateElements.forEach(function(dateElement) {
                                        const dateFinText = dateElement.textContent.trim();
                                        const dateFin = new Date(dateFinText);
                                        const today = new Date();

                                        if (dateFin < today) {
                                            dateElement.classList.add('expired');
                                        }
                                    });
                                });
                            </script>

                            <i style="color: rgba(246, 206, 3, 0.78);" class="stat-cards-info__num">
                                {{ $projet->status }}...
                            </i>
                        </div>
                        <span style="border: 2px solid; padding-top: 100px; margin-left: 50px;"
                            class="stat-cards-info__num"></span>

                        <div class="users-table sign-up-form form container col-xl-8">
                            <div class="main-nav-start">
                                <div class="search-wrapper">
                                    <button id="addMemberButton" title="Cliquez pour ajouter un membre"
                                        data-target="#addMemberModal" data-project-id="{{ $projet->id }}"
                                        style="background: none; display: flex;">
                                        <img src="{{ asset('img/add.png') }}" alt=""
                                            style="width: 32px; height: 32px;">
                                        <p class="stat-cards-info__num"
                                            style="margin: 5px; font-weight: 500; font-size: 20px">Ajouter un membre</p>
                                    </button>

                                    {{-- <button id="addMemberButton" title="Cliquez pour ajouter un membre"
                                data-target="#addMemberModal" style="background: none; display: flex;">
                                <img src="{{ asset('img/add.png') }}" alt="" style="width: 32px; height: 32px;">
                                <p class="stat-cards-info__num" style="margin: 5px; font-weight: 500; font-size: 20px">Ajouter un membre</p>
                            </button> --}}

                                    {{-- MODAL --}}
                                </div>
                            </div>
                            <div class="users-table table-wrapper">
                                <table>
                                    <thead class="stat-cards-info__num">
                                        <tr class="users-table-info">
                                            <th>Nom</th>
                                            <th>Téléphone</th>
                                            <th>Secteur d'Activité</th>
                                            <th>Compétences</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projet->assignations as $assignation)
                                            @foreach ($employees as $employee)
                                                @if ($employee->id == $assignation->employe_id)
                                                    <tr>
                                                        <td>{{ $employee->nom }}</td>
                                                        <td>{{ $employee->telephone }}</td>
                                                        <td>
                                                            @foreach ($employee->tasks as $task)
                                                                {{ $task->sector->namesector }}@if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach ($employee->specialists as $specialist)
                                                                {{ $specialist->competence->namecompetence }}@if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        </td>

                                                        <td>
                                                            <button title="Cliquez pour retirer un membre" class="btn-del"
                                                                data-id="{{ $assignation->id }}">
                                                                <img src="{{ asset('img/eye.png') }}" alt=""
                                                                    style="width: 22px; height: 20px;">
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="display: flex">
                                <button class="archive-btn" title="Cliquez pour archiver tous"
                                    data-id="{{ $projet->id }}" style="background: none; display: flex;">
                                    <img src="{{ asset('img/flo.png') }}" alt=""
                                        style="width: 32px; height: 32px;">
                                    <p class="stat-cards-info__num" style="margin: 5px; font-weight: 500; font-size: 20px">
                                        Archiver</p>
                                </button>
                                <button title="Cliquez pour supprimer tous"
                                    style="background: none; display: flex; margin-left: 30px">
                                    <img src="{{ asset('img/delete.png') }}" alt=""
                                        style="width: 32px; height: 32px;">
                                    <p class="stat-cards-info__num" style="margin: 5px; font-weight: 500; font-size: 20px">
                                        Supprimer tous</p>
                                </button>
                            </div>
                            <span></span>
                        </div>
                    </article>
                </main>
            @endif
        @endforeach
    @endif

    <br>
    </div>

    </head>

    <body>


        <!-- Modal Structure -->
        <div id="addMemberModal" class="modal">
            <div style="width: 60%; margin: 5% auto;" class="users-table table-wrapper sign-up-form form container">
                <span style="font-size: 50px;font-weight:600; cursor: pointer;float: right; color: blue"
                    class="clo">&times;</span>
                <div class="search-wrapper">
                    <input type="text" id="searchInput" placeholder="Enter keywords ..." required>
                </div>

                <br>

                <table class="posts-table" id="employeesTable">
                    <thead>
                        <tr class="users-table-info">
                            <th>
                                <p class="users-table__checkbox ms-20">Sélectionner</p>
                            </th>
                            <th>Profile</th>
                            <th>Nom Complet</th>
                            <th>Telephone</th>
                            <th>Email</th>
                            <th>Secteur d'Activité</th>
                            <th>Compétences</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Les employés seront ajoutés ici -->
                    </tbody>

                </table> <br>
                <div style="text-align: center" id="paginationLinks"></div>
                <button id="addnew" class="form-btn primary-default-btn transparent-btn col-md-4" style="">Ajoute
                    un Membre
                </button>

            </div>
        </div>

        <!-- Inclure la bibliothèque lodash pour la fonctionnalité de debounce -->
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        <script>
            $(document).ready(function() {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                const itemsPerPage = 5; // Nombre d'employés par page
                let currentPage = 1;
                let allEmployees = []; // Pour stocker tous les employés
                let selectedEmployeeIds = new Set();

                loadEmployees(currentPage); // Charger les employés de la première page au chargement de la page

                function loadEmployees(page) {
                    $.ajax({
                        url: '/employees/disponibilite/null', // Endpoint correspondant à votre route Laravel
                        method: 'GET',
                        success: function(response) {
                            allEmployees = response; // Stocker tous les employés
                            displayEmployees(allEmployees,
                                page); // Afficher les employés pour la page actuelle
                            displayPagination(allEmployees.length); // Afficher les liens de pagination
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur lors du chargement des employés:', error);
                        }
                    });
                }

                function displayEmployees(employees, page) {
                    const employeesTable = $('#employeesTable tbody');
                    employeesTable.empty();

                    // Calculer l'indice de début et de fin des employés à afficher
                    const startIndex = (page - 1) * itemsPerPage;
                    const endIndex = Math.min(startIndex + itemsPerPage, employees.length);

                    // Extraire les employés pertinents
                    const employeesToShow = employees.slice(startIndex, endIndex);

                    // Afficher chaque employé dans le tableau
                    employeesToShow.forEach(employee => {
                        const isChecked = selectedEmployeeIds.has(employee.id);
                        const secteurs = employee.secteurs.join(', ');
                        const competences = employee.competences.join(', ');
                        employeesTable.append(`
                <tr>
                    <td>
                        <input type="checkbox" class="employee-checkbox" data-id="${employee.id}" data-profile="${employee.profile}" data-nom="${employee.nom}" data-telephone="${employee.telephone}" data-email="${employee.email}" data-secteur="${secteurs}" data-competence="${competences}" ${isChecked ? 'checked' : ''}>
                    </td>
                    <td><img src="${employee.profile}" alt="Image de Profil" style="width: 50px; height: 50px; border-radius: 50%;"></td>
                    <td>${employee.nom}</td>
                    <td>${employee.telephone}</td>
                    <td>${employee.email}</td>
                    <td>${secteurs}</td>
                    <td>${competences}</td>
                    
                </tr>
            `);
                    });

                    attachEventHandlers();
                }

                function displayPagination(totalItems) {
                    const totalPages = Math.ceil(totalItems / itemsPerPage);
                    const paginationLinks = $('#paginationLinks');
                    paginationLinks.empty();

                    // Afficher les liens de pagination
                    for (let i = 1; i <= totalPages; i++) {
                        paginationLinks.append(`<button class="page-link" data-page="${i}">${i}</button>`);
                    }
                }

                function attachEventHandlers() {
                    // Détacher les gestionnaires d'événements existants pour éviter les doublons
                    $(document).off('change', '.employee-checkbox');

                    // Réattacher les gestionnaires d'événements aux cases à cocher dans le tableau des employés
                    $(document).on('change', '.employee-checkbox', function() {
                        const selectedEmployeesTable = $('#selectedEmployeesTable tbody');
                        const employeeData = $(this).data();
                        const employeeId = employeeData.id;

                        if (this.checked) {
                            selectedEmployeeIds.add(employeeId);
                            selectedEmployeesTable.append(`
                    <tr data-id="${employeeId}">
                        <td>
                            <div class="circl"></div>
                            <div class="overflo-text">${employeeData.nom}</div>
                        </td>
                        <td>${employeeData.telephone}</td>
                        <td>${employeeData.email}</td>
                        <td>${employeeData.secteur}</td>
                    </tr>
                `);
                        } else {
                            selectedEmployeeIds.delete(employeeId);
                            selectedEmployeesTable.find(`tr[data-id="${employeeId}"]`).remove();
                        }
                    });

                    $(document).on('click', '.page-link', function() {
                        const page = $(this).data('page');
                        currentPage = page;
                        displayEmployees(allEmployees, page);
                    });

                    // Gestionnaire d'événements pour la suppression des affectations
                    $(document).on('click', '.btn-del', function() {
                        const button = this;
                        const assignationId = $(this).data('id');

                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "De vouloir retirer cet employer du projet!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oui, je le souhaite!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '{{ route('assignations.destroy', '') }}/' +
                                        assignationId,
                                    type: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire('Suprimer!',
                                                'Employer retire du projet avec success.',
                                                'success');
                                            var row = $(button).closest('tr');
                                            row.remove();
                                            loadEmployees(
                                                currentPage
                                            ); // Recharge les employés après la suppression réussie
                                        } else {
                                            Swal.fire('Error!',
                                                'Une erreur es survenue pendant la revocation.',
                                                'error');
                                        }
                                    },
                                    error: function() {
                                        Swal.fire('Error!',
                                            'Une erreur est survenue pendant la revocation.', 'error'
                                        );
                                    }
                                });
                            }
                        });
                    });
                }

                // Ajouter un gestionnaire d'événements pour la recherche avec debounce pour améliorer les performances
                $('#searchInput').on('input', _.debounce(function() {
                    const searchTerm = $(this).val().toLowerCase();

                    // Filtrer les employés en fonction du terme de recherche
                    const filteredEmployees = allEmployees.filter(employee => {
                        return (
                            employee.nom.toLowerCase().includes(searchTerm) ||
                            employee.competences.some(comp => comp.toLowerCase().includes(
                                searchTerm)) ||
                            employee.secteurs.some(sect => sect.toLowerCase().includes(
                                searchTerm))
                        );
                    });

                    displayEmployees(filteredEmployees, 1); // Afficher les employés filtrés
                    displayPagination(filteredEmployees.length); // Mettre à jour la pagination
                }, 300));

                // $('#addnew').on('click', function() {
                //     console.log("Bouton 'Ajouter' cliqué"); // Ajoutez cette ligne pour déboguer
                //     const projetId = $('#selectProjet').val();
                //     alert(projetId);
                //     const employeeIds = Array.from(selectedEmployeeIds);

                //     console.log("Projet ID:", projetId); // Ajoutez cette ligne pour déboguer
                //     console.log("IDs des employés sélectionnés:",
                //         employeeIds); // Ajoutez cette ligne pour déboguer

                //     $.ajax({
                //         url: '{{ route('addmember') }}',
                //         method: 'POST',
                //         headers: {
                //             'X-CSRF-TOKEN': csrfToken
                //         },
                //         data: {
                //             projet_id: projetId,
                //             employe_id: employeeIds,
                //         },
                //         success: function(response) {
                //             const Toast = Swal.mixin({
                //                 toast: true,
                //                 position: "top-center",
                //                 showConfirmButton: false,
                //                 timer: 1000,
                //                 timerProgressBar: true,
                //                 didOpen: (toast) => {
                //                     toast.onmouseenter = Swal.stopTimer;
                //                     toast.onmouseleave = Swal.resumeTimer;
                //                 }
                //             });
                //             Toast.fire({
                //                 icon: "success",
                //                 title: "Membres ajoutés avec succès"
                //             });
                //             $('#selectProjet').val('');
                //             $('input[name="date"]').val('');
                //             selectedEmployeeIds.clear();
                //             loadEmployees(
                //                 currentPage); // Recharger les employés après l'ajout des membres
                //             $('#selectedEmployeesTable tbody')
                //                 .empty(); // Vider la table des employés sélectionnés
                //         },
                //         error: function(xhr, status, error) {
                //             Swal.fire({
                //                 icon: 'error',
                //                 title: 'Erreur!',
                //                 text: xhr.responseJSON.message,
                //             });
                //         }
                //     });
                // });
                $('#addnew').on('click', function() {
                    console.log("Bouton 'Ajouter' cliqué");
                    const projetId = $('#modalProjectId')
                .val(); // Récupère l'ID du projet depuis le champ caché
                    const employeeIds = Array.from(selectedEmployeeIds);

                    console.log("Projet ID:", projetId);
                    console.log("IDs des employés sélectionnés:", employeeIds);

                    $.ajax({
                        url: '{{ route('addmember') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            projet_id: projetId,
                            employe_id: employeeIds,
                        },
                        success: function(response) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-center",
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success",
                                title: "Membres ajoutés avec succès"
                            });
                            $('#modalProjectId').val(''); // Réinitialiser le champ caché
                            $('input[name="date"]').val('');
                            selectedEmployeeIds.clear();
                            loadEmployees(
                            currentPage); // Recharger les employés après l'ajout des membres
                            $('#selectedEmployeesTable tbody')
                        .empty(); // Vider la table des employés sélectionnés
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur!',
                                text: xhr.responseJSON.message,
                            });
                        }
                    });
                });


                // Attacher les gestionnaires d'événements pour la première fois
                attachEventHandlers();
            });
        </script>

        <div>
            <!-- Votre code HTML pour les tables et autres éléments de l'interface utilisateur -->
        </div>

        <script>
            //   document.addEventListener('DOMContentLoaded', function() {
            //     var addMemberButtons = document.querySelectorAll('[data-target]');
            //     var modals = document.querySelectorAll('.modal');
            //     var closeButtons = document.querySelectorAll('.clo');

            //     addMemberButtons.forEach(function(button) {
            //         button.addEventListener('click', function() {
            //             var targetModalId = this.getAttribute('data-target');
            //             var modal = document.querySelector(targetModalId);
            //             if (modal) {
            //                 modal.style.display = 'block';
            //             }
            //         });
            //     });

            //     closeButtons.forEach(function(closeBtn) {
            //         closeBtn.addEventListener('click', function() {
            //             var modal = this.closest('.modal');
            //             if (modal) {
            //                 modal.style.display = 'none';
            //                 location.reload(); // Rechargez la page ou effectuez d'autres actions nécessaires
            //             }
            //         });
            //     });

            //     window.addEventListener('click', function(event) {
            //         modals.forEach(function(modal) {
            //             if (event.target == modal) {
            //                 modal.style.display = 'none';
            //             }
            //         });
            //     });
            // });
            document.addEventListener('DOMContentLoaded', function() {
                var addMemberButtons = document.querySelectorAll('[data-target]');
                var modals = document.querySelectorAll('.modal');
                var closeButtons = document.querySelectorAll('.clo');

                addMemberButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var targetModalId = this.getAttribute('data-target');
                        var projectId = this.getAttribute('data-project-id'); // Récupère l'ID du projet
                        var modal = document.querySelector(targetModalId);
                        if (modal) {
                            modal.style.display = 'block';
                            document.getElementById('modalProjectId').value =
                            projectId; // Met à jour le champ caché
                        }
                    });
                });

                closeButtons.forEach(function(closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        var modal = this.closest('.modal');
                        if (modal) {
                            modal.style.display = 'none';
                            location
                        .reload(); // Rechargez la page ou effectuez d'autres actions nécessaires
                        }
                    });
                });

                window.addEventListener('click', function(event) {
                    modals.forEach(function(modal) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    });
                });
            });
        </script>



        {{-- ----------------------------------------Touche pas------------------------------------------------------------ --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ajoute un événement de clic pour chaque bouton de suppression
                // Utilisation de jQuery pour la simplicité, mais vous pouvez également utiliser Axios ou fetch API

                document.querySelectorAll('.archive-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        let projetId = this.getAttribute('data-id');
                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "Vous allez archiver ce projet!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oui, archivez-le!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '{{ route('projets.archive', '') }}/' +
                                        projetId,
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire('Archivé!',
                                                'Le projet a été archivé.',
                                                'success');
                                            location.reload();
                                        } else {
                                            Swal.fire('Erreur!',
                                                'Une erreur s\'est produite lors de l\'archivage.',
                                                'error');
                                        }
                                    },
                                    error: function() {
                                        Swal.fire('Erreur!',
                                            'Une erreur s\'est produite lors de l\'archivage.',
                                            'error');
                                    }
                                });
                            }
                        });
                    });
                });
            });
        </script>
    @endsection
