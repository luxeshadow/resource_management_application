<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <style>
        button {
            border: none;
        }
        body {
            margin: 50px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .toggle-password:hover {
            transform: rotate(90deg);
        }
    </style>
    
    <title>Inscription</title>
</head>
<body style="font-family: 'Segoe UI';">
    <div class="layer"></div>
    <main class="page-center">
        <article class="sign-up">
            <h1 class="sign-up__title">Commencez</h1>
            <p class="sign-up__subtitle">Commencez à créer la meilleure expérience utilisateur possible pour vos clients</p>
            <form class="sign-up-form form" style="margin-left:5px;" id="ajouterEmployer" enctype="multipart/form-data">
                @csrf
                <label class="form-label-wrapper">
                    <p class="form-label">Nom Complet</p>
                    <input name="name" id="name" class="form-input" type="text" placeholder="Entrer un nom" autocomplete="off">
                    <div style="color: red;" class="error-message" id="name-error"></div>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Email</p>
                    <input name="email" id="email" class="form-input" type="email" placeholder="Entrer l'email" autocomplete="off">
                    <div style="color: red;" class="error-message" id="email-error"></div>
                </label>
               
                <label class="form-label-wrapper">
                    <p class="form-label">Téléphone</p>
                    <input name="telephone" class="form-input" type="text" placeholder="Entrez le numéro de téléphone" autocomplete="off">
                    <div style="color: red;" class="error-message" id="telephone-error"></div>
                </label>
                
                <label class="form-label-wrapper">
                    <div class="password-container">
                        <p class="form-label">Mot de passe</p>
                        <input class="form-input" type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" autocomplete="off">
                        <button style="background: none; font-size: 30px; color: #A39B9B;" type="button" class="toggle-password">&#128065;</button>
                    </div>
                    <div style="color: red;" class="error-message" id="password-error"></div>
                </label>
                <label class="form-label-wrapper">
                    <p class="form-label">Ajouter une photo de profil</p>
                </label>
                <div class="custom__image-container">
                    <label id="add-img-label" for="add-single-img">
                        <p>+</p><img id="preview-image" alt="Aperçu de l'image sélectionnée" style="display: none;">
                    </label>
                    <input class="form-input" name="photo" type="file" id="add-single-img" accept="image/jpeg">
                    <div style="color: red;" class="error-message" id="photo-error"></div>
                </div>
                <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Ajouter Employé</button>
            </form>
        </article>
    </main>
    <!-- Bibliothèque Chart -->
   

    <script>
        $(document).ready(function() {
            const passwordInput = $('#password');
            const togglePasswordButton = $('.toggle-password');

            togglePasswordButton.on('click', function() {
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
            });

            $('#add-single-img').on('change', function(event) {
                const input = event.target;
                const file = input.files[0];
                const previewImage = $('#preview-image');

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.attr('src', e.target.result);
                        previewImage.show();
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewImage.hide();
                    previewImage.attr('src', '');
                }
            });

            function loadUsers() {
                $.ajax({
                    url: "{{ route('users.index') }}",
                    type: 'GET',
                    success: function(response) {
                        let usersList = $('#users-list');
                        usersList.empty();

                        response.forEach(function(user) {
                            usersList.append(`
                                <tr>
                                    <td><img src="/storage/${user.photo}" alt="Photo de profil" style="width:50px; height:50px;"></td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td><button class="delete-user" data-id="${user.id}">Supprimer</button></td>
                                </tr>
                            `);
                        });
                    },
                    error: function(response) {
                        console.error('Erreur lors du chargement des utilisateurs :', response);
                    }
                });
            }

            $('#ajouterEmployer').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        // Réinitialiser les champs du formulaire
                        $('#ajouterEmployer')[0].reset();
                        $('#preview-image').hide();

                        // Effacer les messages d'erreur
                        $('.error-message').text('');

                        // Recharger la liste des utilisateurs
                        loadUsers();

                        // Afficher un message de succès ou gérer la soumission réussie
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Employé ajouté avec succès!'
                        });
                    },
                    error: function(response) {
                        let errors = response.responseJSON.errors;

                        // Effacer les messages d'erreur précédents
                        $('.error-message').text('');

                        // Afficher les messages d'erreur
                        if (errors.name) {
                            $('#name-error').text(errors.name[0]);
                        }
                        if (errors.email) {
                            $('#email-error').text(errors.email[0]);
                        }
                        if (errors.password) {
                            $('#password-error').text(errors.password[0]);
                        }
                        if (errors.photo) {
                            $('#photo-error').text(errors.photo[0]);
                        }
                        // Afficher un message d'erreur global
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de l\'ajout de l\'employé. Veuillez réessayer.'
                        });
                    }
                });
            });

            loadUsers();
        });
    </script>
</body>
</html>
