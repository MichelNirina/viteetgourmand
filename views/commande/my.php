<h1 class="title">Mes commandes</h1>

<?php if (!empty($success)): ?>
    <div class="success-message">
        <?= $success; ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="error-message">
        <?= $error; ?>
    </div>
<?php endif; ?>

<table class="commande-table">

    <thead>
        <tr>
            <th>Numéro</th>
            <th>Date</th>
            <th>Menu</th>
            <th>Personnes</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php if (!empty($commandes)): ?>

            <?php foreach ($commandes as $c): ?>

                <?php
                    $statut = strtolower($c['statut']);
                ?>

                <tr>

                    <td><?= $c['numero_commande']; ?></td>
                    <td><?= $c['date_commande']; ?></td>
                    <td><?= $c['titre'] ?? 'N/A'; ?></td>
                    <td><?= $c['nombre_personne']; ?></td>

                    <!-- STATUT -->
                    <td>
                        <?php if ($statut == 'en attente'): ?>
                            En attente

                        <?php elseif ($statut == 'en préparation'): ?>
                            En préparation

                        <?php elseif ($statut == 'terminée'): ?>
                            Terminée

                        <?php else: ?>
                            <?= $c['statut']; ?>
                        <?php endif; ?>
                    </td>

                    <!-- ACTIONS -->
                    <td>

                        <?php if ($statut == 'en attente'): ?>

                            <a href="?page=commande&action=edit&id=<?= $c['numero_commande']; ?>">
                                Modifier
                            </a>

                            |

                            <a href="?page=commande&action=delete&id=<?= $c['numero_commande']; ?>"
                               onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')">
                                Annuler
                            </a>

                        <?php elseif ($statut == 'en préparation'): ?>

                            <span style="color:orange;">En cours de traitement</span>

                        <?php elseif ($statut == 'terminée'): ?>

                            <a href="?page=avis&action=create&commande_id=<?= $c['numero_commande']; ?>">
                                Donner un avis
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">Aucune commande trouvée</td>
            </tr>

        <?php endif; ?>

    </tbody>

</table>