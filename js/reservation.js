// recup l'ID du chef depuis l'URL
const urlParams = new URLSearchParams(window.location.search);
const chefId = urlParams.get('chefId');

// rempli le champ caché avec l'ID du chef
if (chefId) {
  document.getElementById('chef_id').value = chefId;
}