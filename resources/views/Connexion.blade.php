<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kofcorporation Dashboard</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/log.png') }}" type="image/x-icon">
    <!-- Custom styles -->
    <link rel="stylesheet" href="./css/style.min.css">

    <script src="{{ asset('js/jquery.js') }}"></script>
</head>

<body style="font-family: 'Segoe UI';">
    <div class="spinner"><img src="{{ asset('img/log.png') }}" alt=""></div>

    <div class="layer"></div>
    <main class="page-center">  
        <article class="sign-up">
            <h1 class="sign-up__title">Content de te revoir!</h1>
            <p style="font-weight:700;" class="sign-up__subtitle">Connectez-vous à votre compte pour continuer</p>
            <form style="width: 400px;" class="sign-up-form form col-xl-12" action="{{ route('auth.verification') }}" method="POST">
                @csrf <!-- Ajout du jeton CSRF pour la protection contre les attaques CSRF -->
                
               
                @if ($errors->has('authentication'))
                <p style="color: red;" class="error-message">
                    {{ $errors->first('authentication') }}
                </p>
               @endif
            
                <label style="font-weight:700;" class="form-label-wrapper">
                    <p class="form-label">Email</p>
                    <input required name="email" class="form-input" type="email" placeholder="Entrer l'email" autocomplete="off" value="{{ old('email') }}">
                         {{--@if ($errors->has('email'))
                        <div style="color: red;" class="error-message">{{ $errors->first('email') }}</div>
                         @endif --}}
                </label>
               
                
                <label class="form-label-wrapper">
                    <p class="form-label">Mots de passe</p>
                    <input required class="form-input" type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" autocomplete="off">
                    <button style="background: none; font-size: 30px;color: #A39B9B" type="button" class="toggle-password">&#128065;
                        <span class="slash" style="position: absolute; width: 2px; height: 25px; background-color: #A39B9B; transform: rotate(45deg); top: 5px; right: 12px; display: none;"></span>
                    </button>
                 
                </label>
                
                <button style="background-color:#002147;" class="form-btn primary-default-btn transparent-btn" type="submit">Connexion</button>
            </form>
        </article>
    </main>

    <!-- Chart library -->
    <script src="./plugins/chart.min.js"></script>
    <!-- Icons library -->
    <script src="plugins/feather.min.js"></script>
    <!-- Custom scripts -->
    <script src="js/script.js"></script>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.querySelector('.toggle-password');
        const slash = document.querySelector('.slash');
    
        togglePasswordButton.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
    
            if (type === 'text') {
                slash.style.display = 'none';
            } else {
                slash.style.display = 'block';
            }
        });
    </script>
   
    <script>
        window.addEventListener('', () => {
            const spinner = document.querySelector('.spinner');
            const pageCenter = document.querySelector('.page-center');

            setTimeout(() => {
                spinner.style.display = 'none';
                pageCenter.style.opacity = '1';
            }, 100); // Temps de chargement du spinner en millisecondes (2 secondes)
        });
    </script>
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            background-color: #f0f0f0;
        }

        .spinner {
            border: 8px solid rgba(0, 0, 0, 0.1);
            border-top: 8px solid #002147;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            position: absolute;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .page-center {
            opacity: 0;
            animation: fadeIn 1s forwards;
            animation-delay: 2s;
            position: absolute;
        }

        .sign-up__title {
            opacity: 0;
            animation: fadeIn 1s forwards;
            animation-delay: 3s;
        }

        .sign-up__subtitle {
            opacity: 0;
            animation: fadeIn 1s forwards;
            animation-delay: 3.5s;
        }


        .toggle-password {
            position: absolute;
           margin-top: 40px;
            right: 60px;
          
            cursor: pointer;
        }

    </style>
</body>

</html>
