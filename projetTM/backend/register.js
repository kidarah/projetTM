$(document).ready(function() {
    $("#form").on("submit", function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: "register.php",
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.errors) {
                    for (var field in response.errors) {
                        $("#" + field + "Error").text(response.errors[field]);
                    }
                } else {
                    alert(response.success);
                    $("#form")[0].reset();
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX:", error);
                alert("Une erreur s'est produite. Veuillez vérifier les logs.");
            }
        });
    });
});