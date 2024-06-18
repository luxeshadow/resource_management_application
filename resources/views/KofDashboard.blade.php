<!DOCTYPE html>
<html lang="en">

<head>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kofcorporation Dashboard | Dashboard</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/log.png') }}" type="image/x-icon">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">

</head>

<body style="font-family: 'Segoe UI'">
    <div class="layer"></div>
    <!-- ! Body -->
    <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
    <div class="page-flex">
        <!-- ! Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-start">
                <div class="sidebar-head">
                    <a href="#" class="logo-wrapper" title="Home">
                        <span class="sr-only">Home</span>
                        <style>
                            h3:hover {
                                letter-spacing: 5px;
                                cursor: pointer;
                            }
                            

                            .img:hover {
                                animation: shake 0.6s linear alternate;
                            }

                            @keyframes shake {
                                0% {
                                    transform: translateX(0px);
                                }

                                25% {
                                    transform: translateX(-5px);
                                }

                                50% {
                                    transform: translateX(10px);
                                }

                                75% {
                                    transform: translateX(-5px);
                                }

                                100% {
                                    transform: translateX(0px);
                                }
                            }
                        </style>
                        <span class="img" aria-hidden="true"><img width="70px" src="{{ asset('img/log.png') }}"
                                alt=""></span>
                        <div class="logo-text">
                            <span class="logo-title">{{ __('messages.kof') }}</span>
                            <span class="logo-subtitle">{{ __('messages.dash') }}</span>
                            
                        </div>

                    </a>
                    <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                        <span class="sr-only">Toggle menu</span>
                        <span class="icon menu-toggle" aria-hidden="true"></span>
                    </button>
                </div>
                <div  class="sidebar-body">
                    <ul class="sidebar-body-menu">
                        <li>
                            <a class="active" href="{{ route('accueil') }}"><span class="icon home"
                                    aria-hidden="true"></span>{{ __('messages.dash') }}</a>
                        </li>
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon document" aria-hidden="true"></span>{{ __('messages.gestion') }}
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="{{ route('CreateEmployer') }}">{{ __('messages.ademp') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('CreateProjet')}}">{{ __('messages.adpro') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('competences.create')}}">{{ __('messages.adcomp') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('sectors.create')}}">{{ __('messages.adsec') }}</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon folder" aria-hidden="true"></span>{{ __('messages.workspace') }}
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="{{ route('Equipe') }}">Create Team</a>
                                </li>
                                <li>
                                    <a href="{{ route('Equipe&Projet') }}">TeamsProjet</a>
                                </li>
                                
                                
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('historique') }}">
                                <span class="icon category" aria-hidden="true"></span>{{ __('messages.historique') }}
                              
                                    <span class="sr-only">Historiques des projets</span>
                                    
                                </span>
                            </a>
                          
                
                        </li>
                        
                        </li>
                        {{-- <script>
                            // JavaScript pour manipuler le compteur de notifications
                            var msgCounter = document.getElementById('msg-counter');
                            var assignationsCount = {{ count($assignationsFinies) }};
                            msgCounter.textContent = assignationsCount;
                        </script> --}}
                    </ul>
                    <span class="system-menu__title">system</span>
                    <ul class="sidebar-body-menu">

                        <li>
                            <a class="show-cat-btn" href="##">
                                <span class="icon user-3" aria-hidden="true"></span>Users
                                <span class="category__btn transparent-btn" title="Open list">
                                    <span class="sr-only">Open list</span>
                                    <span class="icon arrow-down" aria-hidden="true"></span>
                                </span>
                            </a>
                            <ul class="cat-sub-menu">
                                <li>
                                    <a href="{{ route('createUsers') }}">Create User</a>
                                </li>
                                <li>
                                    <a href="users-02.html">Edit User</a>
                                </li>
                            </ul>
                        </li>
                       
                    </ul>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
           
        </aside>
        <div class="main-wrapper">
            <!-- ! Main nav -->
            <nav class="main-nav--bg">
                <div class="container main-nav">
                    <div class="main-nav-start">
                        <div class="row">
                            <span class="img" aria-hidden="true"><img width="42px"
                                    src="{{ asset('img/log.png') }}" alt=""></span>
                            <h3 class="main-title">{{ __('messages.kof') }}</h3>
                        </div>

                    </div>

                    <div class="main-nav-end">
                        <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                            <span class="sr-only">Toggle menu</span>
                            <span class="icon menu-toggle--gray" aria-hidden="true"></span>
                        </button>
                       
                        <div class="lang-switcher-wrapper">
                            <button class="lang-switcher transparent-btn" type="button">
                                {{ strtoupper(App::getLocale()) }}
                                <i data-feather="chevron-down" aria-hidden="true"></i>
                            </button>
                            <ul class="lang-menu dropdown">
                                <li><a href="{{ route('lang.switch', 'en') }}">English</a></li>
                                <li><a href="{{ route('lang.switch', 'fr') }}">French</a></li>
                                <li><a href="{{ route('lang.switch', 'uz') }}">Uzbek</a></li>
                            </ul>
                        </div>
                        
                        <script>
                            document.querySelector('.lang-switcher').addEventListener('click', function() {
                                document.querySelector('.lang-menu').classList.toggle('show');
                            });
                        
                            window.addEventListener('click', function(e) {
                                if (!document.querySelector('.lang-switcher-wrapper').contains(e.target)) {
                                    document.querySelector('.lang-menu').classList.remove('show');
                                }
                            });
                        </script>
                        
                        <style>
                            .lang-menu {
                                display: none;
                                list-style-type: none;
                                position: absolute;
                                
                                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
                            }
                        
                            .lang-menu.show {
                                display: block;
                            }
                        
                            .lang-menu li {
                                padding: 10px;
                            }
                        
                            .lang-menu li a {
                                text-decoration: none;
                                color: black;
                            }
                        
                           
                        </style>
                        
                        
                        <button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
                            <span class="sr-only">Switch theme</span>
                            <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
                            <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
                        </button>
                        
                        <div class="nav-user-wrapper">
                            <button class="dropdown-btn theme-switcher gray-circle-btn" type="button" title="Switch theme">
                                <span class="sr-only">My profile</span>
                                    <i data-feather="user" aria-hidden="true"></i>
                            </button>
                         
                            
                            <ul class="users-item-dropdown nav-user-dropdown dropdown">
                                <li><a href="{{ route('showprofile') }}">
                                        <i data-feather="user" aria-hidden="true"></i>
                                        <span>Profile</span>
                                    </a></li>
                               
                                <li><a class="danger" href="">
                                        <i data-feather="log-out" aria-hidden="true"></i>
                                        <span>Log out</span>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div>
                @yield('accueil')
            </div>

            <div>
                @yield('employer')
            </div>
            <div>
                @yield('projet')
            </div>
            <div>
                @yield('equipe')
            </div>
            <div>
                @yield('equipe&projet')
            </div>

            <div>
                @yield('historique')
            </div>

            <div>
                @yield('register')
            </div>
            <div>
                @yield('profile')
            </div>
            <div>
                @yield('addCompetences')
            </div>
            <div>
                @yield('addSectors')
            </div>


            <!-- ! Footer -->

        </div>

        <!-- Chart library -->
        <script src="{{ asset('plugins/chart.min.js') }}"></script>
        <!-- Icons library -->
        <script src="{{ asset('plugins/feather.min.js') }}"></script>
        <script src="{{ asset('plugins/feather.min.js.map') }}"></script>

        <!-- Custom scripts -->
        <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
