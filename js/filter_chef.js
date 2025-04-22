let chefsData = [];
let currentIndex = 0;

function renderChef(index) {
  const container = $('#chefs-filter');
  container.empty();

  if (chefsData.length === 0) {
    container.html('<p class="text-center">Aucun chef trouvé pour ces critères.</p>');
    return;
  }

  // Ajoute la carte du chef
  const chef = chefsData[index];
  const card = `
  <div class="col-12 col-md-4 d-flex justify-content-center">
    <div class="card p-4 mt-3 swipe-card animate__animated animate__fadeIn">
      <div class="first">
        <h6 class="heading">${chef.prenom} ${chef.nom} – Spécialité : ${chef.specialites}</h6>
        <div class="time d-flex flex-row align-items-center justify-content-between mt-3">
          <div class="d-flex align-items-center">
            <i class="fa fa-clock-o clock"></i>
            <span class="hour ml-1">3 hrs</span>
          </div>
          <div><span class="font-weight-bold">$90</span></div>
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
              <i class="fa fa-star"></i><i class="fa fa-star"></i>
              <i class="fa fa-star"></i><i class="fa fa-star"></i>
              <i class="fa fa-star"></i>
            </div>
          </div>
          <div>
            <button class="btn btn-outline-dark btn-sm px-2">+ follow</button>
            <button class="btn btn-outline-dark btn-sm">See Profile</button>
            <button class="btn btn-outline-dark btn-sm"><i class="fa fa-comment-o"></i></button>
          </div>
        </div> 
      </div>
      <hr class="line-color">
      <h6>48 comments</h6>
      <div class="third mt-4">
        <button class="btn btn-warning btn-block"><i class="fa fa-clock-o"></i> Book Now</button>
      </div>
    </div>
  </div>
`;
  container.append(card);
}

function transitionChef(direction) {
  const currentCard = $('.chef-card'); // Carte actuelle
  const container = $('#chefs-filter');

  // Ajoute une animation de rejet
  const animationOut = direction === 'left' ? 'throw-left' : 'throw-right';
  currentCard.addClass(animationOut);

  // Attends la fin de l'animation avant de passer à la carte suivante
  setTimeout(() => {
    currentIndex = (currentIndex + (direction === 'left' ? 1 : -1) + chefsData.length) % chefsData.length;

    // Supprime la carte actuelle et ajoute la suivante
    currentCard.remove();
    renderChef(currentIndex);

    const nextCard = $('.chef-card');
    nextCard.addClass('fade-in');
  }, 500); // Correspond à la durée de l'animation
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
        chefsData = chefs;
        currentIndex = 0;
        renderChef(currentIndex);
      }
    });
  });

  // Gestes swipe gauche/droite
  const container = document.getElementById('chefs-filter');
  const hammer = new Hammer(container);

  hammer.on('swipeleft', function () {
    transitionChef('left');
  });

  hammer.on('swiperight', function () {
    transitionChef('right');
  });

  // Affiche le premier chef par défaut
  if (chefsData.length > 0) {
    renderChef(currentIndex);
  }
});
