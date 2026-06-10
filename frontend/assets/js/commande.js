document.addEventListener("DOMContentLoaded", function () {

    const nbPersonne = document.getElementById('nb_personne');
    const prixUnitaire = document.getElementById('prix_unitaire');
    const prixTotal = document.getElementById('prix_total');

    function calculer() {
        let nb = parseInt(nbPersonne.value);

        if (!isNaN(nb)) {
            prixTotal.textContent = nb * prixUnitaire.value;
        }
    }

    calculer();

    nbPersonne.addEventListener('input', calculer);
});