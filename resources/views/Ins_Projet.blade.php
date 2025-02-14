@extends('KofDashboard')

@section('projet')
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('ajax/projet.js') }}"></script>

    <style>
        h1:hover {
            letter-spacing: 5px;
            cursor: pointer;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: rgb(12, 34, 236);
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <!-- Modal -->
    <div id="modifierProjetModal" class="modal1">
        <div id="custom-modal-content" style="margin-top: 15px; margin-bottom: 15px;"
            class="sign-up-form form container col-xl-6">
            <div class="custom-modal-header">
                <span class="close">&times;</span>
                <h2 class="main-title">Modifier le Projet</h2>
            </div>
            <form id="modifierProjet">
                @method('PUT')
                <label class="form-label-wrapper">
                    <p class="form-label">Nom Projet</p>
                    <input class="form-input" name="nomprojet" placeholder="Entrer le nom du projet...">
                    <span class="error-message" style="color: red;"></span>
                </label>


                <label class="form-label-wrapper">
                    <p class="form-label">Description</p>
                    <textarea class="form-input" name="description" placeholder="Entrer la description..."></textarea>
                    <span class="error-message" style="color: red;"></span>
                </label>

                <label class="form-label-wrapper">
                    <p class="form-label">Type de Projet</p>
                    <select style="text-transform: capitalize" class="form-input" title="Ouvrir la liste"
                        id="selectTypeProjet" name="typeprojet">
                        <option value="" disabled selected hidden></option>
                    </select>
                    <div style="color: red;" class="error-message"></div>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Nom Client</p>
                    <input class="form-input" name="nomclient" placeholder="Entrer le nom du client...">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Téléphone</p>
                    <input class="form-input" name="telephone" placeholder="Entrer le téléphone du client...">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Email</p>
                    <input class="form-input" name="email" type="email" placeholder="Entrer l'email...">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <button type="submit" class="form-btn primary-default-btn">Modifier Projet</button>
            </form>
        </div>
    </div>
    {{-- fin modification projet --}}

    <h1 class="main-title" style="margin-top: 10px; font-size: 22px; margin:5px">{{ __('messages.adpro') }}</h1><br>
    <div class="row col-xl-12 ">
        <main class="container col-xl-4">
            <form id="ajouterProjet" class="sign-up-form form container" action="" method="POST">
                @csrf

                <label class="form-label-wrapper">
                    <p class="form-label">Nom Projet</p>
                    <input class="form-input" name="nomprojet" type="text" placeholder="Entrer le nom du projet..."
                        autocomplete="off">
                    <span class="error-message" style="color: red;"></span>
                </label>


                <label class="form-label-wrapper">
                    <p class="form-label">Type de Projet</p>
                    <select style="text-transform: capitalize" class="form-input" title="Ouvrir la liste"
                        id="selectTypeProje" name="typeprojet">
                        <option value="" disabled selected hidden></option>
                    </select>
                    <div style="color: red;" class="error-message"></div>
                </label>
                {{-- <script>
                    $(document).ready(function() {
                        // modifier projet
                        $(document).on('click', '.btn-edit', function() {
                            var projetId = $(this).data('id');
                            console.log('Projet ID:', projetId); // Pour confirmer que l'ID est bien récupéré

                            // Charger les données du projet (simuler avec AJAX)
                            $.ajax({
                                url: 'projets/' + projetId + '/edit', // Assurez-vous que cette URL est correcte
                                type: 'GET',
                                success: function(data) {
                                    // Remplir les champs du modal avec les données du projet
                                    $('#modifierProjet input[name="nomprojet"]').val(data.nomprojet);
                                    $('#modifierProjet select[name="typeprojet"]').val(data.typeprojet);
                                    $('#modifierProjet textarea[name="description"]').val(data.description);
                                    $('#modifierProjet input[name="nomclient"]').val(data.nomclient);
                                    $('#modifierProjet input[name="telephone"]').val(data.telephone);
                                    $('#modifierProjet input[name="email"]').val(data.email);
                                    console.log('Données du projet chargées:', data);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Erreur de chargement du projet:', error);
                                }
                            });


                            // Afficher le modal
                            $('#modifierProjetModal').css('display', 'block');
                        });

                        // Fermer le modal lorsque l'utilisateur clique sur la croix
                        $(document).on('click', '.close', function() {
                            $('#modifierProjetModal').css('display', 'none');
                        });

                        $('#modifierProjet').on('submit', function(e) {
                            e.preventDefault();

                            var projetId = $('.btn-edit').data('id');
                            var formData = $(this).serialize();

                            $.ajax({
                                url: 'projets/' + projetId, // Assurez-vous que cette URL est correcte
                                type: 'PUT',
                                data: formData,
                                
                                success: function(response) {
                                    console.log('Projet mis à jour avec succès:', response);
                                    // Fermer le modal après la mise à jour
                                    $('#modifierProjetModal').css('display', 'none');
                                    // Rafraîchir la liste des projets ou mettre à jour l'interface utilisateur selon vos besoins
                                },
                                error: function(xhr, status, error) {
                                    console.error('Erreur lors de la mise à jour du projet:', error);
                                    // Afficher les messages d'erreur dans les champs appropriés
                                    var errors = xhr.responseJSON.errors;
                                    if (errors) {
                                        for (var field in errors) {
                                            $('#modifierProjet input[name="' + field +
                                                    '"], #modifierProjet textarea[name="' + field +
                                                    '"], #modifierProjet select[name="' + field + '"]')
                                                .next('.error-message').text(errors[field][0]);
                                        }
                                    }
                                }
                            });
                        });

                        // Fermer le modal lorsque l'utilisateur clique en dehors du modal
                        $(window).click(function(event) {
                            if ($(event.target).is('#modifierProjetModal')) {
                                $('#modifierProjetModal').css('display', 'none');
                            }
                        });
                        // Vérifie si jQuery est chargé et si le script est exécuté
                        $.ajax({
                            url: '{{ route('typeprojets.index') }}', // Utiliser Blade pour insérer l'URL de la route
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); // Affiche la réponse dans la console pour déboguer
                                var selectTypeProjet = $('#selectTypeProjet');
                                $.each(response, function(index, typeProjet) {
                                    selectTypeProjet.append($('<option>', {
                                        value: typeProjet.id,
                                        text: typeProjet
                                            .nametypeprojet // Assurez-vous que cette clé correspond à votre structure de données
                                    }));
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });

                        $.ajax({
                            url: '{{ route('typeprojets.index') }}', // Utiliser Blade pour insérer l'URL de la route
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); // Affiche la réponse dans la console pour déboguer
                                var selectTypeProjet = $('#selectTypeProje');
                                $.each(response, function(index, typeProjet) {
                                    selectTypeProjet.append($('<option>', {
                                        value: typeProjet.id,
                                        text: typeProjet
                                            .nametypeprojet // Assurez-vous que cette clé correspond à votre structure de données
                                    }));
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });
                </script> --}}
                <script>
                    $(document).ready(function() {
                        // Modifier projet

                        $.ajax({
                            url: '{{ route('typeprojets.index') }}', // Utiliser Blade pour insérer l'URL de la route
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); // Affiche la réponse dans la console pour déboguer
                                var selectTypeProjet = $('#selectTypeProjet');
                                $.each(response, function(index, typeProjet) {
                                    selectTypeProjet.append($('<option>', {
                                        value: typeProjet.id,
                                        text: typeProjet
                                            .nametypeprojet // Assurez-vous que cette clé correspond à votre structure de données
                                    }));
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });

                        $.ajax({
                            url: '{{ route('typeprojets.index') }}', // Utiliser Blade pour insérer l'URL de la route
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                console.log(response); // Affiche la réponse dans la console pour déboguer
                                var selectTypeProjet = $('#selectTypeProje');
                                $.each(response, function(index, typeProjet) {
                                    selectTypeProjet.append($('<option>', {
                                        value: typeProjet.id,
                                        text: typeProjet
                                            .nametypeprojet // Assurez-vous que cette clé correspond à votre structure de données
                                    }));
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });
                </script>
                <label class="form-label-wrapper">
                    <p class="form-label">Description</p>
                    <textarea class="form-input" name="description" cols="30" placeholder="Description du projet..." rows="10"></textarea>
                    <span class="error-message" style="color: red;"></span>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Nom du Client</p>
                    <input class="form-input" name="nomclient" placeholder="Entrer le nom du client..."
                        autocomplete="off">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Telephone</p>
                    <input class="form-input" name="telephone" placeholder="Enter le telephone du client..."
                        autocomplete="off">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Email</p>
                    <input class="form-input" name="email" type="email" placeholder="Entrer l'email..."
                        autocomplete="off">
                    <span class="error-message" style="color: red;"></span>
                </label>
                <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Ajouter
                    Projet</button>
            </form>
            <br>
        </main>


        <main class="col-xl-8">
            <div class="users-table sign-up-form form container">
                <div class="main-nav-start">
                    <div class="search-wrapper">

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
                        <tbody id="projets-list"></tbody>
                    </table>
                </div>
            </div>
            <div class="pagination-wrapper">

            </div>
            <br>
        </main>
    </div>

   

@endsection
