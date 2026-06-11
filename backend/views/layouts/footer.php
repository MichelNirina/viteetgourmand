<footer class="footer">

    <div class="footer-columns">
        <div class="footer-col">
            <p><strong>Horaires</strong></p>
            <?php
            require_once __DIR__ . '/../../models/Horaire.php';
            $horaireModel = new Horaire();
            $horaireFooter = $horaireModel->getGroupedByDay();
            foreach ($horaireFooter as $h):
            ?>
                <p>
                    <strong><?= htmlspecialchars($h['jour']) ?></strong> :
                    <?= htmlspecialchars($h['creneaux']) ?>
                </p>
            <?php endforeach; ?>
        </div>

        <div class="footer-col footer-col-links">
            <a href="?page=mentions">Mentions légales</a>
            <a href="?page=cgv">Conditions générales de vente</a>
        </div>
    </div>

    <p class="footer-copy">&copy; <?= date('Y') ?> Vite &amp; Gourmand - Tous droits réservés</p>

</footer>

<script src="/viteetgourmand/frontend/assets/js/commande.js"></script>

</body>
</html>
