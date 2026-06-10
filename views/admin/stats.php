<h1 class="title">Statistiques</h1>

<div class="stats-container">

    <div class="stats-card total-card">
        <h2>Total commandes</h2>
        <p class="stats-number"><?= $total['total']; ?></p>
    </div>

    <div class="stats-card">
        <h2>Commandes par menu</h2>

        <ul class="stats-list">
            <?php foreach ($byMenu as $b): ?>
                <li>
                    Menu <span><?= $b['menu_id']; ?></span>
                    <strong><?= $b['total']; ?></strong> commandes
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="stats-card">
        <h2>Chiffre d'affaires</h2>

        <ul class="stats-list">
            <?php foreach ($ca as $c): ?>
                <li>
                    Menu <span><?= $c['menu_id']; ?></span>
                    <strong><?= $c['ca']; ?> €</strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>