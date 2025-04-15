
    if (!localStorage.getItem("chefs")) localStorage.setItem("chefs", JSON.stringify([]));
    if (!localStorage.getItem("currentChef")) localStorage.setItem("currentChef", JSON.stringify({ email: "chef@example.com" }));

    function getChefProfile() {
      const current = JSON.parse(localStorage.getItem("currentChef"));
      const chefs = JSON.parse(localStorage.getItem("chefs")) || [];
      let profile = chefs.find(c => c.email === current.email);
      if (!profile) {
        profile = {
          email: current.email,
          nom: "Unknown Chef",
          specialite: "Unknown",
          bio: "No biography available yet.",
          photo: "",
          plats: []
        };
        chefs.push(profile);
        localStorage.setItem("chefs", JSON.stringify(chefs));
      }
      return profile;
    }

    function renderProfile(profile) {
      document.getElementById("chefNom").innerText = profile.nom;
      document.getElementById("chefSpecialite").innerText = "Specialty: " + profile.specialite;
      document.getElementById("chefBio").innerText = profile.bio;
      document.getElementById("chefPhoto").src = profile.photo || "https://source.unsplash.com/120x120/?chef";
      const gallery = document.getElementById("galleryGrid");
      gallery.innerHTML = "";
      if (profile.plats?.length > 0) {
        profile.plats.forEach(src => {
          const img = document.createElement("img");
          img.src = src;
          img.classList.add("gallery-img");
          gallery.appendChild(img);
        });
      } else {
        gallery.innerHTML = "<p class='text-muted'>No dish uploaded yet.</p>";
      }
    }

    function saveProfile() {
      const current = JSON.parse(localStorage.getItem("currentChef"));
      const chefs = JSON.parse(localStorage.getItem("chefs"));
      const index = chefs.findIndex(c => c.email === current.email);
      const updated = {
        ...chefs[index],
        nom: document.getElementById("editNom").value,
        specialite: document.getElementById("editSpecialite").value,
        bio: document.getElementById("editBio").value
      };
      const fileInput = document.getElementById("photoUpload");
      if (fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          updated.photo = e.target.result;
          chefs[index] = updated;
          localStorage.setItem("chefs", JSON.stringify(chefs));
          renderProfile(updated);
        };
        reader.readAsDataURL(fileInput.files[0]);
      } else {
        chefs[index] = updated;
        localStorage.setItem("chefs", JSON.stringify(chefs));
        renderProfile(updated);
      }
      bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    }

    function resetPreview() {
      document.getElementById("photoUpload").value = "";
      document.getElementById("photoPreview").style.display = "none";
    }

    function addDish() {
      const file = document.getElementById("photoPlatFile").files[0];
      if (!file) return;
      const current = JSON.parse(localStorage.getItem("currentChef"));
      const chefs = JSON.parse(localStorage.getItem("chefs"));
      const index = chefs.findIndex(c => c.email === current.email);
      const reader = new FileReader();
      reader.onload = function (e) {
        chefs[index].plats.push(e.target.result);
        localStorage.setItem("chefs", JSON.stringify(chefs));
        renderProfile(chefs[index]);
      };
      reader.readAsDataURL(file);
    }

    document.getElementById("editModal").addEventListener("show.bs.modal", () => {
      const profile = getChefProfile();
      document.getElementById("editNom").value = profile.nom;
      document.getElementById("editSpecialite").value = profile.specialite;
      document.getElementById("editBio").value = profile.bio;
      const preview = document.getElementById("photoPreview");
      if (profile.photo) {
        preview.src = profile.photo;
        preview.style.display = "block";
      } else {
        preview.style.display = "none";
      }
    });

    renderProfile(getChefProfile());
