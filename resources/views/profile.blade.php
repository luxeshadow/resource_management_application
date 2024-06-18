@extends('KofDashboard')
@section('profile')
<link rel="stylesheet" href="./css/style.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <div style="text-align: center;margin: 50px;" class="form">
        <div class="profile-photo">
            <img src="{{ asset('img/vid.png') }}" alt="Photo de profil">
            
        </div>
        <div class="profile-info">
            <h1 class="notification-container stat-cards-info__num" >Baki Hanma
            <span title="connecter" class="notification"></span></h1>
            <p class="stat-cards-info__num" >Email: utilisateur@example.com</p>
            <p class="stat-cards-info__num" >Téléphone: 123-456-7890</p>
           
        </div>
    </div>

<style>



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
        .notification {
            position: absolute;
            top: 5px;
            /* Adjust position as needed */
            right: -10px;
            /* Adjust position as needed */
            background: rgb(4, 240, 110); 
            color: white;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 13px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 10px;
            cursor: pointer;
            /* Adjust size as needed */
            height: 10px;
            box-shadow: #666
            /* Adjust size as needed */
        }

        /* secteur fin selection */
        

.profile-info h1, .profile-info p {
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
@endsection