@extends('KofDashboard')

@section('historique')
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        h2:hover {
            letter-spacing: 5px;
            cursor: pointer;
        }
    </style>
    <br>
    
    <br>

   
    <div class="sign-up-form form col-lg-5" style="height: 5px;margin-left: 20px">
        <div style="margin-top: -40px">
            <p class="form-label">Recherche</p>
            <input  id="search-input" class="form-input" placeholder="Recherche..." autocomplete="off">
        </div>

    
    </div>
    
       
      
   
   
    <style>
        #rech-container {
            position: relative;
        }
        #toggle-rech {
            position: absolute;
            top: 40%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    
       
    </style>

    @foreach($projets as $projet)
        <main class="stat-cards-item" style="margin: 20px">
            <article style="margin-bottom: 20px" class="row">
                <div class="col-xl-3" style="text-align: center;margin-top: 50px">
                    <div title="Nom du projet" class="stat-cards-info__num"
                        style="margin-left: 110px;margin-bottom: 30px;margin-top: 20px;font-weight: 600;text-align: center;">
                        <div class="circle"></div>
                        <div class="overflow-text">{{ $projet->nomprojet }}</div>
                    </div>
                    <br>
                    <br>
                    <p class="stat-cards-info__num">
                        Date de Début: {{ $projet->created_at }}
                    </p>

                    <p class="stat-cards-info__num">
                        <span id="date-fin">Date Fin {{ $projet->first()->date_fin ?? 'N/A' }}</span>
                    </p>
                    <i style="color: rgb(4, 240, 110);" class="stat-cards-info__num">
                        {{ $projet->status }}...
                    </i>
                </div>

                <span style="border:2px solid; padding-top: 100px; margin-left: 50px;" class="stat-cards-info__num"></span>

                <div class="users-table sign-up-form form container col-xl-8">
                    <div class="main-nav-start"></div>
                    <div class="users-table table-wrapper">
                        <table class="">
                            <thead class="stat-cards-info__num">
                                <tr class="users-table-info">
                                    <th>Nom</th>
                                    <th>Téléphone</th>
                                    <th>Secteur d'Activité</th>
                                    <th>Compétences</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projet->assignations as $assignation)
                                    @php
                                        // Récupérer les secteurs et compétences de chaque employé
                                        $sectors = $assignation->employe->sectors->pluck('namesector')->implode(', ');
                                        $competences = $assignation->employe->competences->pluck('namecompetence')->implode(', ');
                                    @endphp
                                    <tr>
                                        <td class="employee-name" style="padding-right: 30px;">{{ $assignation->employe->nom }}</td>
                                        <td style="padding-right: 30px;">{{ $assignation->employe->telephone }}</td>
                                        <td>{{ $sectors }}</td>
                                        <td>{{ $competences }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </main>
    @endforeach
    <br>
    <script>
        // Fonction de recherche
         // Fonction de recherche
    $('#search-input').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.stat-cards-item').each(function() {
            var projectName = $(this).find('.overflow-text').text().toLowerCase();
            var showProject = projectName.includes(searchTerm);
            
            // Recherche par nom d'employé
            $(this).find('.employee-name').each(function() {
                var employeeName = $(this).text().toLowerCase();
                if (employeeName.includes(searchTerm)) {
                    showProject = true;
                }
            });

            if (showProject) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    </script>
@endsection
