document.addEventListener("DOMContentLoaded", function () {
    fetch("../backend/getUserRole.php")
        .then(response => response.json())
        .then(data => {
            if (data.role === "chef") {
                document.getElementById("modifierProfil").style.display = "block";
            }
        })
        .catch(error => console.error("Erreur lors de la récupération du rôle :", error));
});
