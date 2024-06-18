@extends('KofDashboard')

@section('register')
    <link rel="stylesheet" href="./css/style.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <body style="font-family: 'Segoe UI';">
        <h2 title="titre" style="background: none;display: flex;margin-left: 30px;margin-top:30px;margin-bottom: 30px; ">
            <img src="{{ asset('img/person.png') }}" alt="" style="width:60px; height:60px;">
            <p class="stat-cards-info__num" style="margin-top: 10px;font-weight: 700;font-size: 25px;margin-left: 10px">
                {{ __('messages.inscription') }}
            </p>
        </h2>
        <div class="row col-xl-12" style="">
            <main class="col-xl-4">
                <article class="sign-up">
                    <form class="sign-up-form form container"style="margin-left:5px; id="ajouterEmployer" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label class="form-label-wrapper">
                                <p class="form-label">Nom Complet</p>
                                <input name="nom" id="name" class="form-input" type="text"
                                    placeholder="Entrer nom de l'employer">
                                <div style="color: red;" class="error-message"></div>
                            </label>
                            <label class="form-label-wrapper">
                                <p class="form-label">Email</p>
                                <input name="email" class="form-input" type="email" placeholder="Entrer l'email">
                                <div style="color: red;" class="error-message"></div>
                            </label>
                            <label class="form-label-wrapper">
                                <div class="password-container">
                                    <p class="form-label">Password</p>
                                    <input class="form-input" id="password" name="password"
                                        placeholder="Saisissez votre mot de passe">
                                    <button style="background: none; font-size: 30px;color: #A39B9B" type="button"
                                        class="toggle-password">&#128065</button>
                                </div>
                                <div style="color: red;" class="error-message"></div>
                            </label>

                          
                            <label class="form-label-wrapper">
                                <p class="form-label">Add profile picture</p>
                            </label>
                            <div class="custom__image-container">
                                <label id="add-img-label" for="add-single-img">
                                    <p>+</p><img id="preview-image" alt="Selected Image Preview" style="display: none;">
                                </label>
                                <input class="form-input" name="photo" type="file" id="add-single-img"
                                    accept="image/jpeg" />
                            </div>

                            <button type="submit" class="form-btn primary-default-btn transparent-btn col-md-8">Ajouter
                                Employer</button>
                        </form>
                    </article>
                    <br>
                </main>

                <main class="col-xl-8" style="margin-top: 5px">

                    <div class="users-table sign-up-form form container">
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
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="users-list"></tbody>
                            </table>
                        </div>
                    </div>
                </main>

                <!-- Chart library -->
                <script src="./plugins/chart.min.js"></script>
                <!-- Icons library -->
                <script src="plugins/feather.min.js"></script>
                <!-- Custom scripts -->

                <script>
                    document.getElementById('add-single-img').addEventListener('change', function(event) {
                        const input = event.target;
                        const file = input.files[0];
                        const previewImage = document.getElementById('preview-image');

                        if (file) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                                previewImage.style.display = 'block';
                            };

                            reader.readAsDataURL(file);
                        } else {
                            previewImage.style.display = 'none';
                            previewImage.src = '';
                        }
                    });
                </script>
            </div>

            <script>
                const passwordInput = document.getElementById('password');
                const togglePasswordButton = document.querySelector('.toggle-password');

                togglePasswordButton.addEventListener('click', () => {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;

                });
            </script>

            <style>
                .password-container {
                    position: relative;
                }
                .toggle-password {
                    position: absolute;
                    top: 60%;
                   
                    right: 10px;
                    transform: translateY(-50%);
                    cursor: pointer;
                }

                .toggle-password button {
                    transition: all 0.2s ease-in-out;
                }

                .toggle-password:hover button {
                    transform: rotate(90deg);
                }
            </style>
        </body>
@endsection
