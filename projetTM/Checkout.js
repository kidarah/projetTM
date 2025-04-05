// Vérifie si l'utilisateur est connecté
const user = JSON.parse(localStorage.getItem("user"));
if (!user) {
  alert("Veuillez vous connecter pour réserver un chef.");
  window.location.href = "connexion.html";
} else {
  // Pré-remplit nom et email si l'utilisateur est connecté
  window.addEventListener("DOMContentLoaded", () => {
    document.getElementById("nom").value = user.nom || "";
    document.getElementById("email").value = user.email || "";
  });
}

// Gérer le formulaire de réservation
document.getElementById("reservationForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const reservation = {
    chef: document.getElementById("chefSelect").value,
    date: document.getElementById("date").value,
    heure: document.getElementById("heure").value,
    nom: document.getElementById("nom").value,
    email: document.getElementById("email").value,
    paiement: document.getElementById("paiement").value,
  };

  // Enregistrer dans les réservations
  let commandes = JSON.parse(localStorage.getItem("commandes")) || [];
  commandes.push(reservation);
  localStorage.setItem("commandes", JSON.stringify(commandes));

  // Enregistrement pour la confirmation
  localStorage.setItem("derniereReservation", JSON.stringify(reservation));

  window.location.href = "confirmation.html";
});

