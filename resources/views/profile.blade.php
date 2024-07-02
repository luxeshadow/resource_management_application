@extends('KofDashboard')

@section('profile')
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <div style="text-align: center; margin: 50px;" class="form">
        <div class="profile-photo">
            <img src="{{ asset('storage/' . session('user.photo')) }}" alt="Photo de profil"
                style="width: 150px; height: 150px;">
        </div>
        <div class="profile-info">
            <h1 class="notification-container stat-cards-info__num">{{ session('user.name') }}
                <button style="background: none;" class="edit-profile-button" data-id="{{ auth()->user()->id }}">
                    <img title="Modifier mon profil" width="30px" src="{{ asset('img/pen.png') }}" alt="Modifier le profil">
                </button>
            </h1>
            <p class="stat-cards-info__num">Email: {{ session('user.email') }}</p>
            <p class="stat-cards-info__num">Telephone: {{ session('user.telephone') }}</p>
        </div>
    </div>

    <!-- Modal -->
<!-- Bouton de Modification de Profil -->


<!-- Modal de Modification de Profil -->
<div id="editUserModal" class="modal">
    <div style="margin-top: 30px; margin-bottom: 50px;" class="sign-up-form form container col-xl-6">
        <span class="close">&times;</span>
        <form id="editUserForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-user-id" name="id">
            <label class="form-label-wrapper">
                <p class="form-label">Nom</p>
                <input name="name" id="edit-name" class="form-input" type="text" placeholder="Entrez votre nom">
                <div style="color: red;" class="error-message"></div>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Email</p>
                <input name="email" id="edit-email" class="form-input" type="email" placeholder="Entrez votre email">
                <div style="color: red;" class="error-message"></div>
            </label>
            <label class="form-label-wrapper">
                <p class="form-label">Telephone</p>
                <input name="telephone" id="edit-telephone" class="form-input" type="text" placeholder="Entrez votre telephone">
                <div style="color: red;" class="error-message"></div>
            </label>
          
            <label class="form-label-wrapper">
                <p class="form-label">profile</p>
            </label>
            <div class="custom__image-container">
                <label id="add-img-label" for="add-single-img">
                    <p>+</p><img id="preview-image" alt="Selected Image Preview" style="display: none;">
                </label>
                <input class="form-input" name="photo" type="file" id="add-single-img" accept="image/jpeg" />
            </div>
            
            <!-- Ajoutez d'autres champs de formulaire si nécessaire -->
            <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Modifier Profil</button>
        </form>
    </div>
</div>
<script>
    $('#add-single-img').change(function() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#preview-image').attr('src', e.target.result);
            $('#preview-image').css('display', 'block');
        };

        reader.readAsDataURL(input.files[0]);
    }
});

</script>
<!-- Script JavaScript pour la gestion du modal et de la modification -->
<script>

$(document).ready(function() {
    $('.edit-profile-button').click(function() {
        var userId = $(this).data('id');
        console.log('User ID:', userId);
        $.ajax({
            url: '/users/' + userId + '/edit',
            type: 'GET',
            success: function(user) {
                console.log('Détails de l\'utilisateur:', user);
                $('#edit-user-id').val(user.id);
                $('#edit-name').val(user.name);
                $('#edit-email').val(user.email);
                $('#edit-telephone').val(user.telephone);
               
                $('#edit-password').val(user.password); 
                $('#editUserModal').css('display', 'block');
            },
            error: function(xhr, status, error) {
                console.log('Erreur lors du chargement des détails de l\'utilisateur:', error);
            }
        });
    });

    $('#editUserForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var userId = $('#edit-user-id').val();
        console.log('Formulaire soumis. User ID:', userId);
        console.log('FormData:', formData);
        
        $.ajax({
            url: '/users/' + userId,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                console.log('Réponse du serveur:', response);
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Profil modifié avec succès",
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#editUserModal').css('display', 'none');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.log('Erreur lors de la soumission du formulaire:', error);
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        var errorMessage = errors[key][0];
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

    $('.close').click(function() {
        $(this).closest('.modal').css('display', 'none');
    });
});




</script>


    <!-- Styles pour le modal -->
    <style>
        /* Styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: rgb(115, 145, 165) solid 2px;
            margin-bottom: 20px;
            animation: fadeInPhoto 2s forwards;
        }

        .notification-container {
            position: relative;
            display: inline-block;
            margin-left: 10px;
        }

        .profile-info h1,
        .profile-info p {
            opacity: 0;
            animation: fadeInText 2s forwards;
        }

        .profile-info h1 {
            margin: 10px 0;
            font-size: 24px;
            animation-delay: 2s;
        }

        .profile-info p {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
            animation-delay: 2.5s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInPhoto {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeInText {
            to {
                opacity: 1;
            }
        }
    </style>

    <!-- Script JavaScript pour la gestion du modal -->
    
@endsection
