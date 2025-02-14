@extends('KofDashboard')

@section('equipe')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        h1:hover {
            letter-spacing: 5px;
            cursor: pointer;
        }
    </style>
    

    <main class="container">
        <h1 class="sign-up__title" style="margin-top: 20px;">Former une Equipe</h1>
        <div class="users-table table-wrapper sign-up-form form container">
            <div class="row">
                <label class=" col-lg-3">
                    <p class="form-label">Sélectionner un Projet</p>
                    <select class="form-input"
                        style="width: 200px; height: 35px; text-transform: uppercase;font-weight: 600; "
                        title="Ouvrir la liste" id="selectProjet">
                        <option value="" disabled selected hidden></option>
                    </select>
                    <style>
                        select option {
                            font-weight: 600;
                        }

                        input {
                            width: 20px;
                            font-weight: 600;
                        }
                    </style>
                </label>
                <br>
                <label class="col-lg-2" style="margin-top: -5px">
                    <p for="dateInput" class="form-label">Sélectionnez date d'échéance:</p>
                    <input id="date" class="form-input" type="date" name="date" placeholder="JJ/MM/AAAA">
                    <p id="errorMessage"></p>
                    <script>
                        const today = new Date().toISOString().split('T')[0];
                        // Définir la valeur minimale du champ de date sur la date actuelle
                        $('#date').attr('min', today);
                    </script>


                </label>
        </div>
            <script>
                $(document).ready(function() {
                    $.ajax({
                        url: '/projets/en-cours-ou-nuls',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var selectProjet = $('#selectProjet');
                            $.each(response, function(index, projet) {
                                selectProjet.append($('<option>', {
                                    value: projet.id,
                                    text: projet.nomprojet
                                }));
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            </script>
            {{-- Debut tableau --}}
            <table class="posts-table" id="selectedEmployeesTable">
                <thead>
                    <tr class="users-table-info">
                        <th>Nom Complet</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Secteure d'activiter</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Employees selected will be added here -->
                </tbody>
            </table>
            <br>
            <button id="createTeamBtn" class="form-btn primary-default-btn transparent-btn col-md-4" style="">CREE UNE
                EQUIPE</button>
            {{-- Fin tableau --}}
        </div>
    </main>

    <main class="container">
        <h1 class="sign-up__title" style="margin-top: 20px;">Liste des Employés</h1>
        <br>
        <div class="users-table table-wrapper sign-up-form form container">
            <div class="search-wrapper">
                <i data-feather="search" aria-hidden="true"></i>
                <input type="text" id="searchInput" placeholder="Enter keywords ..." required>
            </div>
            <br>
            <table class="posts-table" id="employeesTable">
                <thead>
                    <tr class="users-table-info">
                        <th>
                            <p class="users-table__checkbox ms-20">
                                Selectionner
                            </p>
                        </th>
                        <th>Profile</th>
                        <th>Nom Complet</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Secteur d'Activiter</th>
                        <th>Competences</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Employees will be added here -->
                </tbody>
            </table>
        </div>
        <div id="paginationLinks" class="pagination-wrapper">
            <!-- Pagination links will be added here -->
        </div>
        <br>
        </div>
    </main>

    <script src="{{ asset('js/jquery.js') }}"></script>

    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            const itemsPerPage = 5; // Nombre d'employés par page
            let currentPage = 1;
            let allEmployees = []; // Pour stocker tous les employés
            let selectedEmployeeIds = new Set(); // Pour stocker les IDs des employés sélectionnés
    
            loadEmployees(currentPage); // Charger les employés de la première page au chargement de la page
    
            function loadEmployees(page) {
                $.ajax({
                    url: '/employees/disponibilite/null', // Endpoint correspondant à votre route Laravel
                    method: 'GET',
                    success: function(response) {
                        allEmployees = response; // Stocker tous les employés
                        displayEmployees(allEmployees, page); // Afficher les employés pour la page actuelle
                        displayPagination(allEmployees.length); // Afficher les liens de pagination
                        attachEventHandlers(); // Réattacher les gestionnaires d'événements
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
                    <td><img src="${employee.profile}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;"></td>
                    <td>${employee.nom}</td>
                    <td>${employee.telephone}</td>
                    <td>${employee.email}</td>
                    <td>${secteurs}</td>
                    <td>${competences}</td>
                </tr>
            `);
        });
            }
    
            function displayPagination(totalItems) {
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                const paginationLinks = $('#paginationLinks');
                paginationLinks.empty();
    
                // Afficher les liens de pagination
                for (let i = 1; i <= totalPages; i++) {
                    paginationLinks.append(
                        `<button class="page-link" data-page="${i}">${i}</button>`
                    );
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
            }
    
            $(document).on('click', '.page-link', function() {
                const page = $(this).data('page');
                currentPage = page;
                displayEmployees(allEmployees, page);
            });
    
            // Ajouter un gestionnaire d'événements pour la recherche
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
    
                // Filtrer les employés en fonction du terme de recherche
                const filteredEmployees = allEmployees.filter(employee => {
                    return (
                        employee.nom.toLowerCase().includes(searchTerm) ||
                        employee.competences.some(comp => comp.toLowerCase().includes(searchTerm)) ||
                        employee.secteurs.some(sect => sect.toLowerCase().includes(searchTerm))
                    );
                });
    
                displayEmployees(filteredEmployees, 1); // Afficher les employés filtrés
                displayPagination(filteredEmployees.length); // Mettre à jour la pagination
            });
    
    
    
    
            $('#createTeamBtn').on('click', function() {
                const projetId = $('#selectProjet').val();
                const date = $('input[name="date"]').val();
                const employeeIds = Array.from(selectedEmployeeIds);
    
    
                $.ajax({
                    url: '{{ route('assignations.store') }}',
                    method: 'POST',
                    headers: {
                        // Ajoute le jeton CSRF à l'en-tête de la requête AJAX
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        projet_id: projetId,
                        date_fin: date,
                        employe_id: employeeIds,
                    },
    
                    success: function(response) {
                        // Afficher une alerte SweetAlert de succès
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                        });
                        $('#selectProjet').val('');
                        $('input[name="date"]').val('');
                        selectedEmployeeIds.clear();
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Afficher une alerte SweetAlert d'erreur
    
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
    
@endsection
