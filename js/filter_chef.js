$(document).ready(function () {
  $('#filter-form').on('submit', function (e) {
    e.preventDefault(); 

   
    let specialty = $('#specialty').val();
    let city = $('#city').val();

    $.ajax({
      url: 'backend/filter_chef.php', 
      method: 'GET',
      data: { specialty, city }, 
      dataType: 'json',
      success: function (chefs) {
        let container = $('#chefs-filter'); 
        container.empty(); 

        //si tableau contient des chefs
        if (chefs.length > 0) {
          chefs.forEach(chef => {
        
            let card = `
          <div class="col-md-4">
            <div class="card h-100 shadow-sm">
              <img src="${chef.photo}" class="card-img-top" alt="${chef.prenom} ${chef.nom}">
              <div class="card-body">
                <h5 class="card-title">${chef.prenom} ${chef.nom}</h5>
                <p class="card-text">Spécialité : ${chef.specialites}</p>
                <a href="Profil-Chefs.html" class="btn btn-primary">Voir le profil</a>
              </div>
            </div>
          </div>`;
            container.append(card); 
          });
        } else {
       
          container.html('<p class="text-center">Aucun chef trouvé pour ces critères.</p>');
        }
      },
      
    });
  });
});