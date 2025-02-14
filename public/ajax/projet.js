
$(document).ready(function() {

    $('#ajouterProjet').on('submit', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut du formulaire
        console.log('Form submitted');

        var formData = new FormData(this);

        $.ajax({
            url: '/projets', // URL de la route définie dans Laravel
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Success:', response);
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Projet enregistré avec succès",
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#ajouterProjet')[0].reset();
                loadProjets(); // Recharger les Projets après ajout
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    // Afficher les erreurs dans le formulaire
                    Object.keys(errors).forEach(function(key) {
                        var errorMessage = errors[key][0]; // Prendre le premier message d'erreur
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

    $('.form-input').on('input', function() {
        $(this).siblings('.error-message').html('');
    });

    // Modifier Projet

    $(document).on('click', '.btn-edit', function() {
        var projetId = $(this).data('id');
        console.log('Projet ID:', projetId); // Pour confirmer que l'ID est bien récupéré

        // Charger les données du projet (simuler avec AJAX)
        $.ajax({
            url: 'projets/' + projetId + '/edit', // Assurez-vous que cette URL est correcte
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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

    // Soumettre le formulaire de modification du projet
    $('#modifierProjet').on('submit', function(e) {
        e.preventDefault();

        var projetId = $('.btn-edit').data('id');
        var formData = $(this).serialize();

        $.ajax({
            url: 'projets/' + projetId, // Assurez-vous que cette URL est correcte
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Projet mis à jour avec succès'
                            
                        }).then(function() {
                            $('#modifierProjetModal').hide();
                           
                        });
                console.log('Projet mis à jour avec succès:', response);
                // Fermer le modal après la mise à jour
                $('#modifierProjetModal').css('display', 'none');
                // Rafraîchir la liste des projets
                loadProjets();
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

    loadProjets();

    // AFFICHER LES PROJETS

    let allProjets = [];
    let currentPage = 1;
    const itemsPerPage = 6;

    function loadProjets() {
        $.ajax({
            url: '/projets',
            type: 'GET',
            success: function(response) {
                allProjets = response.data;
                displayEmployers(allProjets.slice(0, itemsPerPage));
                setupPagination(allProjets.length);
                console.log(allProjets);
            },
            error: function(xhr, status, error) {
                console.log('Erreur lors du chargement des projets:', error);
            }
        });
    }

    function displayEmployers(projets) {
        var projetsList = $('#projets-list');
        projetsList.empty();

        projets.forEach(function(projet) {
            var projetRow = `
                <tr>
                  <td>
                      <div class="circle"></div>
                      <div class="overflow-text">${projet.nomprojet}</div>
                  </td>
                  <td>${projet.typeprojet.nametypeprojet}</td>
                  <td>${projet.nomclient}</td>
                  <td>${projet.telephone}</td>
                  <td>
                      <button title="Supprimer" class="btn-del" data-id="${projet.id}">
                          <img src="img/delete.png" alt="" style="width:23px; height:23px;">
                      </button>
                      <span class="sr-only">Supprimer</span>
                      <button class="btn-edit" data-id="${projet.id}">
                          <img src="img/edi.png" alt="" style="width: 25px; height:25px;">
                      </button>
                      <button id="openModalBtn" class="btn-profile" data-id="${projet.id}">
                          <img src="img/view.png" alt="" style="width: 24px; height:27px;">
                      </button>
                  </td>
                </tr>
            `;
            projetsList.append(projetRow);
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
            var pageButton = `
                <button class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
            `;
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
            displayEmployers(allProjets.slice(startIndex, endIndex));
            setupPagination(allProjets.length);
        }
    });

    $(document).on('click', '#prev-page', function() {
        if (currentPage > 1) {
            currentPage--;
            var startIndex = (currentPage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            displayEmployers(allProjets.slice(startIndex, endIndex));
            setupPagination(allProjets.length);
        }
    });

    $(document).on('click', '#next-page', function() {
        var totalPages = Math.ceil(allProjets.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            var startIndex = (currentPage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            displayEmployers(allProjets.slice(startIndex, endIndex));
            setupPagination(allProjets.length);
        }
    });

});
