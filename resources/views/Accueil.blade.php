@extends('KofDashboard')

@section('accueil')
    <script src=""></script>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>

    <style>
        h2:hover {
            letter-spacing: 3px;
            cursor: pointer;
        }

        .notification-container {
            position: relative;
            display: inline-block;
            margin-top: 30px;
            margin-left: 15px;
        }

        /* secteur fin selection */
        .notification-count {
            position: absolute;
            top: -10px;
            /* Adjust position as needed */
            right: -10px;
            /* Adjust position as needed */
            background: rgb(252, 98, 98);
            color: white;
            border-radius: 50%;
            padding: 2px 5px;
            font-size: 13px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            cursor: pointer;
            /* Adjust size as needed */
            height: 20px;
            /* Adjust size as needed */
        }
    </style>





    <main id="page-center" class="main users chart-page" id="skip-target">
        <div class="container">
            <div style="display: flex">

                <h2 style="margin-top: 20px" class="main-title">{{ __('messages.dash') }}

                </h2>

                <a href="{{ route('Equipe&Projet') }}" class="notification-container" id="notificationContainer">
                    <div title="Nombre de projet a rendre aujourd'hui" class="notification-count" id="notificationCount">
                    </div>

                </a>

                <script>
                    $(document).ready(function() {
                        $.ajax({
                            url: '/notification',
                            method: 'GET',
                            success: function(data) {
                                console.log(data);
                                if (data.count > 0) {
                                    $('#notificationCount').text(data.count);

                                    $('#notificationContainer').show();

                                } else {
                                    $('#notificationContainer').hide();
                                }
                            }
                        });
                    });
                </script>

            </div>


            <div class="row stat-cards">

                <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">
                        <div class="stat-cards-icon purple">
                            <img width="300px" src="{{ asset('img/idea.png') }}" alt="">
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num">{{ $totalProjets }}</p>
                            <p class="stat-cards-info__title">Total projets</p>

                        </div>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="stat-cards-item">

                        <div class="stat-cards-icon success">
                            <img width="300px" src="{{ asset('img/division.png') }}" alt="">
                        </div>
                        <div class="stat-cards-info">
                            <p class="stat-cards-info__num">{{ $totalEmployees }}</p>
                            <p class="stat-cards-info__title">Total employees</p>

                        </div>
                    </article>
                </div>
                <div class="col-md-6 col-xl-4">

                    <canvas id="secteurChart" width="400" height="300"
                        style="max-width: 500px; max-height: 700px; margin: px"></canvas>
                </div>
            </div>
            <h2 class="main-title">{{ __('messages.langpro') }}</h2>
            <div class="row">
                <div class="col-lg-11">
                    <div class="chart">
                        <canvas id="competenceChart" width="500" height="300"></canvas>

                    </div>

                    <h2 id="main-title" class="main-title">Rapport Statistique</h2>


                    <div class="chart">
                        <canvas id="projectComparisonChart" width="800" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.highcharts.com/highcharts.js"></script>


    <!-- Debugging output -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des compétences
            const competenceCtx = document.getElementById('competenceChart').getContext('2d');
            @if (isset($competenceData))
                const competenceData = @json($competenceData);

                const competenceLabels = Object.keys(competenceData);
                const competenceValues = Object.values(competenceData);

                // Définir les couleurs pour chaque compétence
                const competenceColors = [
                    'rgba(128, 0, 128, 1)', // Pourpre
                    'rgba(0, 128, 128, 1)', // Sarcelle
                    'rgba(186, 85, 211, 1)', // Orchidée moyenne
                    'rgba(255, 99, 132, 1)', // Rouge
                    'rgba(54, 162, 235, 1)', // Bleu
                    'rgba(218, 165, 32, 1)', // Or
                    'rgba(255, 206, 86, 1)', // Jaune
                    'rgba(75, 192, 192, 1)', // Vert
                    'rgba(153, 102, 255, 1)', // Violet
                    'rgba(255, 159, 64, 1)', // Orange
                    'rgba(200, 33, 105, 1)', // Gris
                    'rgba(40, 209, 19, 1)', // Vert
                    'rgba(255, 99, 132, 1)', // Rouge
                    'rgba(255, 69, 0, 1)', // Rouge orange
                    'rgba(100, 10, 155, 100)', // Violet
                    'rgba(25, 15, 6, 1)', // Orange
                    'rgba(11, 11, 10, 1)', // Gris
                    'rgba(0, 255, 127, 1)', // Vert de printemps
                    'rgba(255, 20, 147, 1)', // Rose profond
                    'rgba(0, 191, 255, 1)', // Bleu ciel profond
                    'rgba(255, 140, 0, 1)', // Orange foncé
                    'rgba(124, 252, 0, 1)', // Vert chartreuse
                    'rgba(32, 178, 170, 1)', // Vert pâle
                    'rgba(30, 144, 255, 1)', // Bleu dodger
                    'rgba(144, 238, 144, 1)', // Vert pâle
                    'rgba(199, 21, 133, 1)' // Rose vif
                    

                ];

                const competenceBorderColors = [
                    'rgba(255, 99, 132, 1)', // Rouge
                    'rgba(54, 162, 235, 1)', // Bleu
                    'rgba(255, 206, 86, 1)', // Jaune
                    'rgba(75, 192, 192, 1)', // Vert
                    'rgba(153, 102, 255, 1)', // Violet
                    'rgba(255, 159, 64, 1)', // Orange
                    'rgba(199, 199, 199, 1)' // Gris


                ];

                new Chart(competenceCtx, {
                    type: 'doughnut', 
                    data: {
                        labels: competenceLabels,
                        datasets: [{
                            label: 'Nombre d\'employés',
                            data: competenceValues,
                            backgroundColor: competenceColors,
                            borderColor: competenceBorderColors,
                            borderWidth: 0.2,
                            doughnutHoleRadius: 0.5, // Réduire le rayon du trou à 0.5
                            aspectRatio: 1.2 // Augmenter le rapport d'aspect à 1.2
                            // Ajouter le border radius
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        layout: {
                            padding: {
                                left: 10,
                                right: 10,
                                top: 10,
                                bottom: 10
                            }
                        },
                        indexAxis: 'x', // L'affichage des barres horizontales
                        responsive: true,
                        maintainAspectRatio: false // Désactivez le maintien du rapport d'aspect
                        // Réduire la largeur des barres
                    }
                });
            @else
                console.error("Les données de compétences ne sont pas disponibles.");
            @endif

            // Graphique des secteurs
            const secteurCtx = document.getElementById('secteurChart').getContext('2d');
            @if (isset($secteurData))
                const secteurData = @json($secteurData);

                const secteurLabels = Object.keys(secteurData);
                const secteurValues = Object.values(secteurData);

                new Chart(secteurCtx, {
                    type: 'bar',
                    data: {
                        labels: secteurLabels,
                        datasets: [{
                            label: 'Nombre d\'employés',
                            data: secteurValues,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)', // Bleu
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)', // Dev Web
                            ],
                            borderWidth: 1,
                            barThickness: 50
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.raw;
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            @else
                console.error("Les données de secteurs ne sont pas disponibles.");
            @endif

            // Fonction pour générer une couleur aléatoire
            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            // Initialisation du graphique
            const projectComparisonCtx = document.getElementById('projectComparisonChart').getContext('2d');

            const projectComparisonChart = new Chart(projectComparisonCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [
                        @foreach ($projectsData as $nomTypeProjet => $data)
                            {
                                label: '{{ $nomTypeProjet }}',
                                data: {!! json_encode($data) !!},
                                fill: false,
                                borderColor: getRandomColor(), // Utilisation d'une fonction pour obtenir une couleur aléatoire
                                tension: 0.4,
                                borderWidth: 2
                                //fill: true
                            },
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            stacked: false // Assurez-vous que les valeurs ne sont pas empilées
                        },
                        x: {
                            type: 'category',
                            labels: {!! json_encode($months) !!} // Assurez-vous que l'axe X utilise les mois corrects
                        }
                    }
                }
            });

            // Ajoutez un gestionnaire d'événements pour changer les couleurs des graphiques lorsque vous cliquez sur le titre
            document.getElementById('main-title').addEventListener('click', function() {
                projectComparisonChart.data.datasets.forEach(dataset => {
                    dataset.borderColor = getRandomColor();
                });
                projectComparisonChart.update();
            });




        });
    </script>
@endsection
