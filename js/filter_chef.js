let chefsData = [];
let currentIndex = 0;

function renderChef(index) {
  console.log("Données reçues dans renderChef :", chefsData);

  const container = $('#chefs-filter');
  container.empty();

  if (chefsData.length === 0) {
    container.html('<p class="text-center">Aucun chef trouvé pour ces critères.</p>');
    return;
  }

  if (isMobile()) {
    // Mobile : une seule carte affichée à la fois
    const chef = chefsData[index];
    const card = `
      <div class="col-12 d-flex justify-content-center card-container">
        <div class="card p-4 mt-3 chef-card h-100 animate__animated animate__fadeIn">
          <div class="first">
            <h6 class="heading">${chef.prenom} ${chef.nom} – Spécialité : ${chef.specialites}</h6>
            <div class="time d-flex flex-row align-items-center justify-content-between mt-3">
              <div class="d-flex align-items-center">
                <i class="fa fa-clock-o clock"></i>
                <span class="hour ml-1">1 hrs</span>
              </div>
              <div><span class="font-weight-bold">${chef.tarif_horaire}€</span></div>
            </div>
          </div>
          <div class="second d-flex flex-row mt-2">
            <div class="image mr-3">
              <img src="${chef.photo}" class="rounded-circle" width="60"/>
            </div>   
            <div>
              <div class="d-flex flex-row mb-1">
                <span>@${chef.nom}  </span>
                  <div class="ratings ml-2">
                  ${chef.note_moyenne}<i class="fa fa-star"></i>
                </div>
              </div>
              <div>
                <button class="btn btn-outline-dark btn-sm see-profile" data-id="${chef.id}">Voir profil</button> 
              </div>
            </div> 
          </div>
          <hr class="line-color">
          <h6>${chef.nombre_avis} comments</h6>
          <div class="third mt-4">
            <button class="btn btn-warning btn-block book-now" data-chef-id="${chef.id}"><i class="fa fa-clock-o"></i>Réserver</button>
          </div>
        </div>
      </div>
    `;
    container.append(card);
    $(document).on('click', '.see-profile', function () {
      const chefId = $(this).data('id');
  
      if (!chefId) {
          console.error("Erreur : ID du chef non défini.");
          return;
      }
  
      console.log("Redirection vers le profil du chef avec ID :", chefId);
      window.location.href = `../Create_Profil.html?id=${chefId}`;

  });
  
  
  } else {
    // Desktop : afficher toutes les cartes
    console.log("Type de chefsData :", typeof chefsData);
console.log("Valeur de chefsData :", chefsData);
    chefsData.forEach((chef) => {
      const card = `
        <div class="col-12 col-md-4 d-flex justify-content-center card-container">
          <div class="card p-4 mt-3 chef-card h-100 animate__animated animate__fadeIn">
            <div class="first">
              <h6 class="heading">${chef.prenom} ${chef.nom} – Spécialité : ${chef.specialites}</h6>
              <div class="time d-flex flex-row align-items-center justify-content-between mt-3">
                <div class="d-flex align-items-center">
                  <i class="fa fa-clock-o clock"></i>
                  <span class="hour ml-1">1 hrs</span>
                </div>
                <div><span class="font-weight-bold">${chef.tarif_horaire}€</span></div>
              </div>
            </div>
            <div class="second d-flex flex-row mt-2">
              <div class="image mr-3">
                <img src="${chef.photo}" class="rounded-circle" width="60"/>
              </div>   
              <div>
                <div class="d-flex flex-row mb-1">
                  <span>@${chef.nom}</span>
                    <div class="ratings ml-2">
                    ${chef.note_moyenne}<i class="fa fa-star"></i>
                  </div>
                </div>
                <div>
                  <button class="btn btn-outline-dark btn-sm see-profile" data-id="${chef.id}">Voir profil</button>
                </div>
              </div> 
            </div>
            <hr class="line-color">
            <h6>${chef.nombre_avis} comments</h6>
            <div class="third mt-4">
              <button class="btn btn-warning btn-block book-now" data-chef-id="${chef.id}"><i class="fa fa-clock-o"></i>Réserver</button>
            </div>
          </div>
        </div>
      `;
     
      container.append(card);
    });
    $(document).on('click', '.see-profile', function () {
      const chefId = $(this).data('id');
  
      if (!chefId) {
          console.error("Erreur : ID du chef non défini.");
          return;
      }
  
      console.log("Redirection vers le profil du chef avec ID :", chefId);
     window.location.href = `../Create_Profil.html?id=${chefId}`;

  });
  
  
  }

  // ajout id chef
  $('.book-now').on('click', function () {
    const chefId = $(this).data('chef-id');
    window.location.href = `Reservation.html?chefId=${chefId}`;
  });
}

function transitionChef(direction) {
  const currentCard = $('.chef-card');
  const container = $('#chefs-filter');

  const animationOut = direction === 'left' ? 'throw-left' : 'throw-right';
  currentCard.addClass(animationOut);

  setTimeout(() => {
    currentIndex = (currentIndex + (direction === 'left' ? 1 : -1) + chefsData.length) % chefsData.length;
    currentCard.remove();
    renderChef(currentIndex);

    const nextCard = $('.chef-card');
    nextCard.addClass('fade-in');
  }, 500);
}

$(document).ready(function () {
  $('#filter-form').on('submit', function (e) {
    e.preventDefault();

    const specialty = $('#specialty').val();
    const city = $('#city').val();

    $.ajax({
      url: 'backend/filter_chef.php',
      method: 'GET',
      data: { specialty, city },
      dataType: 'json',
      success: function (chefs) {
        console.log("Chefs reçus :", chefs); // 🔍 Vérifier la réponse JSON
        chefsData = chefs;
        currentIndex = 0;
        renderChef(currentIndex);
    
        if (isMobile()) {
          setupSwipe();
        }
      },
      error: function(xhr, status, error) {
        console.error("Erreur AJAX :", error);
      }
    });
    
  });

  if (chefsData.length > 0) {
    renderChef(currentIndex);

    if (isMobile()) {
      setupSwipe();
    }
  }
});

function setupSwipe() {
  const container = document.getElementById('chefs-filter');
  const hammer = new Hammer(container);

  hammer.on('swipeleft', function () {
    transitionChef('left');
  });

  hammer.on('swiperight', function () {
    transitionChef('right');
  });
}

function isMobile() {
  return window.innerWidth <= 768;
}
