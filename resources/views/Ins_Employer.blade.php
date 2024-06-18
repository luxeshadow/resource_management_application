@extends('KofDashboard')

@section('employer')

    <head>

        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('js/employee.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <style>
        h1:hover {
            letter-spacing: 5px;
            cursor: pointer;
        }
    </style>

    <body>

        {{-- ----------------------- MODALE SECTOR --------------------- --}}

        <!-- Modal pour les secteurs -->
        <div id="secteurModal" class="modal">
            <div class="modal-content">
                <span class="closee">&times;</span>
                <h2>Choisir des Secteurs d'Activité</h2>
                <br>
                <div style="display: flex" id="secteurList">
                    <!-- La liste des secteurs sera chargée ici via AJAX -->
                </div>
                <br>
            </div>
        </div>

        <!-- Modal pour les compétences -->
        <div id="competenceModal" class="modal">
            <div class="modal-content">
                <span class="clos">&times;</span>
                <h2>Choisir des Compétences</h2>
                <br>
                <div style="display: flex" id="competenceList">
                    <!-- La liste des compétences sera chargée ici via AJAX -->
                </div>
                <br>
            </div>
        </div>
        <script>
            $(document).on('click', '#openSecteurModalBtn', function() {
                $('#secteurModal').css('display', 'block');

                // Charger les secteurs via AJAX
                $.ajax({
                    url: '{{ route('sectors.index') }}',
                    type: 'GET',
                    success: function(response) {
                        console.log("Réponse de la requête AJAX pour les secteurs :", response);
                        var secteurList = $('#secteurList');
                        secteurList.empty();
                        response.forEach(function(secteur) {
                            secteurList.append(
                                '<div><input style="margin-left:10px;" type="checkbox" class="secteurCheckbox" name="selectedSecteurs[]" value="' +
                                secteur.id + '"> ' + secteur.namesector + '</div>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Erreur lors du chargement des secteurs:', error);
                    }
                });
            });

            // Ouvrir le modal des compétences
            $(document).on('click', '#openCompetenceModalBtn', function() {
                $('#competenceModal').css('display', 'block');

                // Charger les compétences via AJAX
                $.ajax({
                    url: '{{ route('competences.index') }}',
                    type: 'GET',
                    success: function(response) {
                        console.log("Réponse de la requête AJAX pour les compétences :", response);
                        var competenceList = $('#competenceList');
                        competenceList.empty();
                        response.forEach(function(competence) {
                            competenceList.append(
                                '<div><input style="margin-left:10px;" type="checkbox" class="competenceCheckbox" name="selectedCompetences[]" value="' +
                                competence.id + '"> ' + competence.namecompetence + '</div>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Erreur lors du chargement des compétences:', error);
                    }
                });
            });

            // Fermer le modal des secteurs quand on clique sur la croix
            $(document).on('click', '.closee', function() {
                $('#secteurModal').css('display', 'none');
                updateSelectedSecteursDisplay();
            });

            // Fermer le modal des compétences quand on clique sur la croix
            $(document).on('click', '.clos', function() {
                $('#competenceModal').css('display', 'none');
                updateSelectedCompetencesDisplay();
            });

            // Mettre à jour l'affichage des secteurs sélectionnés
            function updateSelectedSecteursDisplay() {
                var selectedSecteurs = [];
                var selectedIds = [];
                $('.secteurCheckbox:checked').each(function() {
                    var label = $(this).parent().text().trim();
                    selectedSecteurs.push(label);
                    selectedIds.push($(this).val());
                });

                var selectedSecteursDisplay = $('#selectedSecteursDisplay');
                var selectedSecteursIds = $('#selectedSecteursIds');
                selectedSecteursDisplay.empty();
                selectedSecteursIds.val(selectedIds.join(','));

                if (selectedSecteurs.length > 0) {
                    selectedSecteursDisplay.append('<p>Secteurs sélectionnés :</p>');
                    selectedSecteurs.forEach(function(secteur) {
                        selectedSecteursDisplay.append('<li>' + secteur + '</li>');
                    });
                } else {
                    selectedSecteursDisplay.append('<p style="margin:10px">Aucun secteur sélectionné</p>');
                }
            }

            // Mettre à jour l'affichage des compétences sélectionnées
            function updateSelectedCompetencesDisplay() {
                var selectedCompetences = [];
                var selectedIds = [];
                $('.competenceCheckbox:checked').each(function() {
                    var label = $(this).parent().text().trim();
                    selectedCompetences.push(label);
                    selectedIds.push($(this).val());
                });

                var selectedCompetencesDisplay = $('#selectedCompetencesDisplay');
                var selectedCompetencesIds = $('#selectedCompetencesIds');
                selectedCompetencesDisplay.empty();
                selectedCompetencesIds.val(selectedIds.join(','));

                if (selectedCompetences.length > 0) {
                    selectedCompetencesDisplay.append('<p>Compétences sélectionnées :</p>');
                    selectedCompetences.forEach(function(competence) {
                        selectedCompetencesDisplay.append('<li>' + competence + '</li>');
                    });
                } else {
                    selectedCompetencesDisplay.append('<p style="margin:10px">Aucune compétence sélectionnée</p>');
                }
            }

            // Mettre à jour l'affichage des secteurs sélectionnés quand on change l'état des cases à cocher
            $(document).on('change', '.secteurCheckbox', function() {
                updateSelectedSecteursDisplay();
            });

            // Mettre à jour l'affichage des compétences sélectionnées quand on change l'état des cases à cocher
            $(document).on('change', '.competenceCheckbox', function() {
                updateSelectedCompetencesDisplay();
            });
        </script>




        {{-- --------------- MODALE SECTOR FIN -------------------------- --}}

        {{-- ---------------------- MODALE COMPETENCES --------------------- --}}




        {{-- --------------- MODALE COMPETENCE FIN -------------------------- --}}

        {{-- -------------------------- MODAL ------------------------- --}}
        <section>

            <div id="myModal" class="modal">

                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="profile-container">
                        <div class="profile-header">
                            <img id="modal-profile" src="" alt="Photo de profil">
                            <h2 id="modal-nom"></h2>
                        </div>

                        <div class="profile-info">
                            <ul>
                                <li>Languages de programation : <span id="modal-competence"></li>
                                <li>Secteur : <span id="modal-secteur"></span> </li>
                                <li>Nombre de projet realisé : <span id="total-projects-assigned"></span> </li>
                            </ul>

                            <h3> <img width="20px" src="{{ asset('img/phone.png') }}" alt=""> Contact </h3>
                            <ul>

                                <li>Telephone : <span id="modal-telephone"></span></li>
                                <li>Email : <span style="text-transform: lowercase" id="modal-email"></span></li>

                            </ul>
                        </div>
                    </div>
        </section>
        {{-- --------------------------------- Fin Modal --------------------- --}}

        {{-- -------------------------- MODAL MODIFIER EMPLOYER ------------------------- --}}
        <section>

            <div id="editModal" class="modal">
                <div class="sign-up-form form container col-xl-6">
                    <span class="close">&times;</span>
                    <form id="modifierEmployer">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit-employee-id" name="id">
                        <label class="form-label-wrapper">
                            <p class="form-label">Nom Complet</p>
                            <input name="nom" id="edit-nom" class="form-input" type="text"
                                placeholder="Entrer nom de l'employer">
                            <div style="color: red;" class="error-message"></div>
                        </label>
                        <label class="form-label-wrapper">
                            <p class="form-label">Telephone</p>
                            <input name="telephone" id="edit-telephone" class="form-input" placeholder="Enter le telephone">
                            <div style="color: red;" class="error-message"></div>
                        </label>
                        <label class="form-label-wrapper">
                            <p class="form-label">Telephone</p>
                            <input name="email" id="edit-email" class="form-input" placeholder="Enter le telephone">
                            <div style="color: red;" class="error-message"></div>
                        </label>
                        <label class="form-label-wrapper">
                            <p class="form-label">Secteur d'Activite</p>
                            <select name="secteur" id="edit-secteur" class="form-input" title="Open list">
                                <option value="" disabled selected hidden></option>
                                <option value="dev mobile">DEV MOBILE</option>
                                <option value="dev web">DEV WEB</option>
                            </select>
                            <div style="color: red;" class="error-message"></div>
                        </label>
                        <label class="form-label-wrapper">
                            <p class="form-label">Competence</p>
                            <input name="competence" id="edit-competence" class="form-input"
                                placeholder="Laravel JavaScript ReactNative...">
                            <div style="color: red;" class="error-message"></div>
                        </label>
                        <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Modifier
                            Employer</button>
                    </form>
                </div>
            </div>
        </section>
        {{-- --------------------------------- Fin Modal Modifier --------------------- --}}

        <h1 class="main-title" style="margin-top: 10px; font-size: 22px; margin:5px">{{ __('messages.ademp') }}</h1><br>
        <script>
            $(document).ready(function() {
                // Fonction pour mettre à jour les secteurs sélectionnés
                $('#ajouterEmployer').on('submit', function(event) {
                    event.preventDefault(); // Empêche la soumission par défaut du formulaire

                    // Mettre à jour les champs cachés avec les valeurs sélectionnées
                    function updateSelectedSecteursDisplay() {
                        var selectedSecteurs = [];
                        $('.secteurCheckbox:checked').each(function() {
                            var label = $(this).parent().text().trim();
                            selectedSecteurs.push(label);
                        });
                        $('#selectedSecteursDisplay').empty(); // Supprimer tous les enfants
                    }

                    function updateSelectedCompetencesDisplay() {
                        var selectedCompetences = [];
                        $('.competenceCheckbox:checked').each(function() {
                            var label = $(this).parent().text().trim();
                            selectedCompetences.push(label);
                        });
                        $('#selectedCompetencesDisplay').empty(); // Supprimer tous les enfants
                    }

                    var formData = new FormData(this);

                    $.ajax({
                        url: '{{ route('employees.store') }}', // URL de la route définie dans Laravel
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            // Mettre à jour les secteurs et les compétences sélectionnés
                            updateSelectedSecteursDisplay();
                            updateSelectedCompetencesDisplay();
                            alert('qshduegfweyifvgewf')
                            // Afficher le message de succès
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Employé enregistré avec succès",
                                showConfirmButton: false,
                                timer: 1000
                            });

                            // Réinitialiser le formulaire
                            $('#ajouterEmployer')[0].reset();
                            $('#preview-image')
                                .hide(); // Masquer l'image de prévisualisation après la soumission
                            loadEmployers(); // Recharger les employeurs après ajout
                        },

                        error: function(xhr, status, error) {
                            var errors = xhr.responseJSON.errors;
                            if (errors) {
                                // Afficher les erreurs dans le formulaire
                                Object.keys(errors).forEach(function(key) {
                                    var errorMessage = errors[key][
                                        0
                                    ]; // Prendre le premier message d'erreur
                                    $('[name="' + key + '"]').siblings('.error-message')
                                        .html(errorMessage);
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

                // Effacer les messages d'erreur lors de la saisie dans les champs du formulaire
                $('.form-input').on('input', function() {
                    $(this).siblings('.error-message').html('');

                });
                // Gestionnaire d'événement pour le clic sur "Sélectionner une ou des Compétences"
                $('#openCompetenceModalBtn').click(function() {
                    $('#selectedCompetencesIds').siblings('.error-message').html('');
                });

                // Gestionnaire d'événement pour le clic sur "Sélectionner des Secteurs d'Activité"
                $('#openSecteurModalBtn').click(function() {
                    $('#selectedSecteursIds').siblings('.error-message').html('');
                });



                // Code pour la prévisualisation de l'image
                const singleImageInput = document.getElementById('add-single-img');
                const previewImage = document.getElementById('preview-image');

                singleImageInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            previewImage.style.display = 'block'; // Afficher l'image de prévisualisation
                        };

                        reader.readAsDataURL(this.files[0]);
                    }
                });
                $(document).on('click', '.btn-del', function() {
                    var id = $(this).data('id');
                    var row = $(this).closest('tr');

                    Swal.fire({
                        title: "Etes vous sure?",
                        text: "Vous allez supprimer un employer!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Annuler",
                        confirmButtonText: "Oui,je suis sure!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('employees.destroy', '') }}/' + id,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },

                                success: function(response) {
                                    allEmployers = allEmployers.filter(emp => emp.id !==
                                    id);
                                    row.remove();
                                    setupPagination(allEmployers.length);
                                    var startIndex = (currentPage - 1) * itemsPerPage;
                                    var endIndex = startIndex + itemsPerPage;
                                    displayEmployers(allEmployers.slice(startIndex,
                                        endIndex));

                                    Swal.fire({
                                        title: "Suprimer!",
                                        text: "Employer suprimer avec success.",
                                        icon: "success"
                                    });
                                },

                                error: function(xhr, status, error) {
                                    console.log(
                                        'Erreur lors de la suppression de l\'employé:',
                                        error);
                                    Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: " l\'employé ne peut etre suprimer car il est actuellement assigner a un projet pour le suprimer veuillez d'abord revoquer son assignation"
                                });
                                }
                            });
                        }

                    });
                });

               

                loadEmployers();
                // Fonction pour charger et afficher les employeurs
                let allEmployers = [];
                let currentPage = 1;
                const itemsPerPage = 5;

                function loadEmployers() {
                    $.ajax({
                        url: '{{ route('employees.index') }}',
                        type: 'GET',
                        success: function(response) {
                            allEmployers = response.data;
                            displayEmployers(allEmployers.slice(0, itemsPerPage));
                            setupPagination(allEmployers.length);
                        },
                        error: function(xhr, status, error) {
                            console.log('Erreur lors du chargement des employeurs:', error);
                        }
                    });
                }

                function displayEmployers(employers) {
                    var employersList = $('#employers-list');
                    employersList.empty();

                    employers.forEach(function(employer) {
                        var profileUrl = '{{ asset('storage/img') }}/' + employer.profile;
                        var secteurs = employer.secteurs.join(
                            ', '); // Concaténer les secteurs d'activité
                        var employerRow = `
                <tr>
                    <td><img src="${profileUrl}" alt="Profile Picture" style="width: 55px; height: 55px; border: #DADBE4 2px solid; border-radius: 50%;"></td>
                    <td>${employer.nom}</td>
                    <td>${employer.telephone}</td>
                    <td>${employer.email}</td>
                  
                    <td>
                        <button title="Supprimer" class="btn-del" data-id="${employer.id}"><img src="img/sup.png" alt="" style="width:22px;"></button>
                        <span class="sr-only">Supprimer</span>
                        <button title="Modifier" class="btn-edit" data-id="${employer.id}"><img src="img/edit.png" alt="" style="width: 25px;"></button>
                        <span class="sr-only">Modifier</span>
                        <button title="Voir plus" id="openModalBtn" class="btn-profile" data-id="${employer.id}"><img src="img/profil.png" alt="" style="width: 25px;"></button>
                        <span class="sr-only">Voir plus</span>
                    </td>
                </tr>
            `;
                        employersList.append(employerRow);
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
                        displayEmployers(allEmployers.slice(startIndex, endIndex));
                        setupPagination(allEmployers.length);
                    }
                });

                $(document).on('click', '#prev-page', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        var startIndex = (currentPage - 1) * itemsPerPage;
                        var endIndex = startIndex + itemsPerPage;
                        displayEmployers(allEmployers.slice(startIndex, endIndex));
                        setupPagination(allEmployers.length);
                    }
                });

                $(document).on('click', '#next-page', function() {
                    var totalPages = Math.ceil(allEmployers.length / itemsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        var startIndex = (currentPage - 1) * itemsPerPage;
                        var endIndex = startIndex + itemsPerPage;
                        displayEmployers(allEmployers.slice(startIndex, endIndex));
                        setupPagination(allEmployers.length);
                    }
                });
                //Rechercher employers
                $('.search-wrapper input').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    var filteredEmployers = allEmployers.filter(function(employer) {
                        return employer.nom.toLowerCase().includes(searchTerm);
                    });
                    currentPage = 1; // Reset to first page
                    displayEmployers(filteredEmployers.slice(0, itemsPerPage));
                    setupPagination(filteredEmployers.length);
                });




                // Charger les employeurs lors du chargement de la page
                $(document).ready(function() {
                    const modal = document.getElementById("myModal");

                    // Gérer l'affichage du modal avec les détails de l'employé
                    $(document).on('click', '.btn-profile', function() {
                        modal.style.display = "block";
                        var employeeId = $(this).data('id');
                        $.ajax({
                            url: '/employees/' +
                                employeeId, // Mettez à jour l'URL de l'API Laravel
                            type: 'GET',
                            success: function(response) {
                                var profileUrl = '/storage/img/' + response.profile;
                                $('#modal-profile').attr('src', profileUrl);
                                $('#modal-nom').text(response.nom);
                                $('#modal-telephone').text(response.telephone);
                                $('#modal-email').text(response.email);
                                $('#modal-secteur').text(response.secteurs.join(
                                    ', ')); // Concaténer les secteurs
                                $('#modal-competence').text(response.competences.join(
                                    ', ')); // Concaténer les compétences
                                $('#total-projects-assigned').text(response
                                    .total_projects_assigned);
                            },
                            error: function(xhr, status, error) {
                                console.log(
                                    'Erreur lors du chargement des détails de l\'employé:',
                                    error);
                            }
                        });
                    });

                    // Fermer le modal lorsqu'on clique sur le bouton de fermeture
                    $('.close').click(function() {
                        $('#myModal').hide();
                    });

                    // Fermer le modal lorsqu'on clique en dehors de celui-ci
                    $(window).click(function(event) {
                        if (event.target == modal) {
                            $('#myModal').hide();
                        }
                    });
                });





            });
        </script>

        <div class="row col-xl-12">


            <main class="col-xl-4">
                <form class="sign-up-form form container" id="ajouterEmployer" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label-wrapper">
                        <p class="form-label">Nom Complet</p>
                        <input name="nom" id="name" class="form-input" type="text"
                            placeholder="Entrer nom de l'employer">
                        <div style="color: red;" class="error-message"></div>
                    </label>

                    <label class="form-label-wrapper">
                        <p class="form-label">Telephone</p>
                        <input name="telephone" class="form-input" placeholder="Enter le telephone">
                        <div style="color: red;" class="error-message"></div>
                    </label>
                    <label class="form-label-wrapper">
                        <p class="form-label">Email</p>
                        <input name="email" class="form-input" type="email" placeholder="Entrer l'email">
                        <div style="color: red;" class="error-message"></div>
                    </label>
                    <div class="form-label">
                        <p class="line" title="Cliquez pour choisir des secteurs d'activité" id="openSecteurModalBtn">
                            Sélectionnées Secteur d'Activite</p>
                        <div id="selectedSecteursDisplay" style="margin-top: 10px;"></div>

                        <!-- Champ caché pour les IDs des secteurs sélectionnés -->
                        <input type="hidden" id="selectedSecteursIds" name="selectedSecteursIds">
                        <div style="color: red;" class="error-message"></div>
                    </div>

                    <div class="form-label">
                        <p class="line" title="Cliquez pour choisir des compétences" id="openCompetenceModalBtn">
                            Sélectionnées une ou des Compétences</p>
                        <div id="selectedCompetencesDisplay" style="margin-top: 10px;"></div>
                        <div style="color: red;" class="error-message"></div>
                        <!-- Champ caché pour les IDs des compétences sélectionnées -->
                        <input type="hidden" id="selectedCompetencesIds" name="selectedCompetencesIds">
                    </div>

                    <label class="form-label-wrapper">
                        <p class="form-label">Add profile picture</p>
                    </label>
                    <div class="custom__image-container">
                        <label id="add-img-label" for="add-single-img">
                            <p>+</p><img id="preview-image" alt="Selected Image Preview" style="display: none;">
                        </label>
                        <input class="form-input" name="profile" type="file" id="add-single-img"
                            accept="image/jpeg" />
                        <div style="color: red;" class="error-message"></div>
                    </div>


                    <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Ajouter
                        Employer</button>
                </form>
                <br>
            </main>
            <main class="col-xl-8">
                <div class="users-table sign-up-form form container">
                    <div class="main-nav-start">

                        <div class="search-wrapper">

                            <input type="text" placeholder="Entrer un nom ..." required>

                        </div>
                        <div class="users-table table-wrapper" id="listeEmployer">
                            <table class="">
                                <thead class="stat-cards-info__num">
                                    <tr class="users-table-info">
                                        <th>Profile</th>
                                        <th>Nom Complet</th>
                                        <th>Telephone</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="employers-list"></tbody>

                            </table>
                        </div>
                    </div>

                </div>
                <div class="pagination-wrapper">

                </div>
                <br>

            </main>
        </div>
    </body>
@endsection
