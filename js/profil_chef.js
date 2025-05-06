document.addEventListener("DOMContentLoaded", function () {
    console.log("Chargement du script profil_chef.js...");
    
    const urlParams = new URLSearchParams(window.location.search);
    const chefId = urlParams.get("id");

    if (!chefId) {
        console.error("Erreur : ID du chef non trouvé dans l'URL.");
        return;
    }

    console.log("ID du chef récupéré :", chefId);

    fetch(`../backend/get_chef.php?id=${chefId}`)
        .then(response => response.json())
        .then(chef => {
            if (chef.error) {
                console.error("Erreur serveur :", chef.error);
                return;
            }

            console.log("Données du chef récupérées :", chef);

            // Mettre à jour la page avec les données du chef
            document.getElementById("chefNom").innerText = chef.prenom + " " + chef.nom; ;
            document.getElementById("chefSpecialite").innerText = "Spécialité : " + chef.specialites;
            document.getElementById("chefBio").innerText = chef.experience || "Aucune biographie disponible";
            document.getElementById("chefPhoto").src = chef.photo || "default.jpg";
            document.getElementById("chefRating").innerText = "★ " + (chef.note_moyenne || "Pas de note");

            // Ajouter les plats dans la galerie
            const gallery = document.getElementById("galleryGrid");
            gallery.innerHTML = "";
            (chef.plats || []).forEach(src => {
                const img = document.createElement("img");
                img.src = src;
                img.classList.add("gallery-img");
                gallery.appendChild(img);
            });

            // Ajouter les avis
            const reviewsContainer = document.getElementById("reviewsContainer");
            reviewsContainer.innerHTML = "";
            if (chef.avis && chef.avis.length > 0) {
                chef.avis.forEach(review => {
                    const reviewElement = document.createElement("p");
                    reviewElement.innerText = `★ ${review.note} - ${review.commentaire}`;
                    reviewsContainer.appendChild(reviewElement);
                });
            } else {
                reviewsContainer.innerHTML = "<p class='empty-reviews'>Aucun avis pour le moment.</p>";
            }
        })
        .catch(error => {
            console.error("Erreur lors de la récupération des données du chef :", error);
        });
});
