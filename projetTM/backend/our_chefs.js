$(document).ready(function () {

  $.ajax({
    url: 'backend/our_chefs.php', 
    method: 'GET',
    dataType: 'json',
    success: function (chefs) {
      let container = $('#chefs-container'); 
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
    },
  });
});