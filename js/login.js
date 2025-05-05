
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Empêche l'envoi traditionnel du formulaire

        const formData = new FormData(form);

        fetch("backend/login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Rediriger vers index.html si connexion réussie
                window.location.href = "index.html";
            } else {
                // Afficher l'erreur retournée par PHP
                showError(data.error || "Erreur de connexion.");
            }
        })
        .catch(error => {
            console.error("Erreur de requête :", error);
            showError("Une erreur est survenue.");
        });
    });

    function showError(message) {
        let errorElement = document.getElementById("loginError");
        if (!errorElement) {
            errorElement = document.createElement("p");
            errorElement.id = "loginError";
            errorElement.style.color = "red";
            form.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }
});
