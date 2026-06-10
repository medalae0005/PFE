// Confirmation avant suppression
let deleteButtons = document.querySelectorAll(".btn-delete");

deleteButtons.forEach(function(button) {

    button.addEventListener("click", function(event) {

        let confirmation = confirm("Voulez-vous vraiment supprimer cet élément ?");

        if (!confirmation) {
            event.preventDefault();
        }

    });

});