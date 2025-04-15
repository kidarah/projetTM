document.addEventListener("DOMContentLoaded", function () {

    let map = L.map('map').setView([50.85045, 4.34878], 8);

    // ajoute OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

});
