$(document).ready(function() {
    $(".form").on("submit", function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        // Vider les messages d'erreur précédents
        $(".form span[id$='Errors']").text('');

        $.ajax({
            url: "backend/register.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.errors) {
                    for (var field in response.errors) {
                        $("#" + field).text(response.errors[field]);
                    }
                } else {
                    alert(response.message);
                    $(".form")[0].reset();
                }
            },
        });
    });
});
