// Afficher / masquer le mot de passe
function togglePassword() {

    let passwordInput = document.getElementById("password");

    let toggleText = document.querySelector(".toggle-password");

    if (passwordInput.type === "password") {

        passwordInput.type = "text";
        toggleText.innerHTML = "Hide";

    } else {

        passwordInput.type = "password";
        toggleText.innerHTML = "Show";
    }
}

// Focus pour la barre de recherche...
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.querySelector('input[name="search"]');

    if (searchInput && searchInput.value.trim() !== '') {

        const results = document.querySelector('.table-container');

        if (results) {
            results.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }

});
