<link rel="stylesheet" href="assets/css/home.css">

<div class="home-container">

    <h1>Bienvenue chez Vite & Gourmand</h1>

    <p>
        Votre traiteur à Bordeaux depuis 25 ans.
        Julie et José vous accompagnent pour tous vos événements.
    </p>

    <a href="?page=menu" class="btn">
        Découvrir nos menus
    </a>

    <hr>

    <h2>Notre professionnalisme</h2>

    <div class="card">

        <h3>Cuisine de qualité</h3>
        <p>Des menus savoureux préparés avec soin.</p>

    </div>

    <div class="card">

        <h3>Livraison rapide</h3>
        <p>Une équipe professionnelle et organisée.</p>

    </div>

    <div class="card">

        <h3>Événements</h3>
        <p>Mariages, anniversaires, Noël, Pâques.</p>

    </div>

    <hr>

    <h2>Avis clients</h2>

    <?php if (!empty($avis)): ?>

        <?php foreach ($avis as $a): ?>

            <div class="card">

                <p>
                    <?= $a['description']; ?>
                </p>

                <p>
                    Note : <?= $a['note']; ?>/5
                </p>

                <strong>
                    <?= $a['prenom']; ?>
                </strong>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Aucun avis disponible.</p>

    <?php endif; ?>

</div>