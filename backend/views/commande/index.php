<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<h1 class="title">Liste des commandes</h1>

<?php if ($_SESSION['user']['role_id'] == 3): ?>
    <a href="?page=commande&action=create">Ajouter une commande</a>
<?php endif; ?>

<br><br>

<table class="commande-table">

    <thead>
        <tr>
            <th>N°</th>
            <th>Date commande</th>
            <th>Date prestation</th>
            <th>Heure</th>
            <th>Prix menu</th>
            <th>Personnes</th>
            <th>Statut</th>
            <th>Client</th>
            <th>Menu</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($commandes as $commande): ?>

            <tr>

                <td><?= $commande['numero_commande']; ?></td>
                <td><?= $commande['date_commande']; ?></td>
                <td><?= $commande['date_prestation']; ?></td>
                <td><?= $commande['heure_livraison']; ?></td>
                <td><?= $commande['prix_menu']; ?> €</td>
                <td><?= $commande['nombre_personne']; ?></td>
                <td><?= $commande['statut']; ?></td>
                <td><?= $commande['user_id']; ?></td>
                <td><?= $commande['menu_id']; ?></td>

                <td>

                <!-- EMPLOYÉ + ADMIN -->
                <?php if ($_SESSION['user']['role_id'] == 2 || $_SESSION['user']['role_id'] == 1): ?>

                    <a href="?page=commande&action=editEmploye&id=<?= $commande['numero_commande']; ?>">
                        Modifier statut
                    </a>

                <?php endif; ?>


                <!-- CLIENT -->
                <?php if ($_SESSION['user']['role_id'] == 3): ?>

                    <?php if ($commande['statut'] != 'acceptée'): ?>

                        <a href="?page=commande&action=edit&id=<?= $commande['numero_commande']; ?>">
                            Modifier
                        </a>

                        |

                        <a href="?page=commande&action=delete&id=<?= $commande['numero_commande']; ?>"
                        onclick="return confirm('Supprimer cette commande ?')">
                            Supprimer
                        </a>

                    <?php else: ?>

                        <span style="color:gray;">Action verrouillée</span>

                    <?php endif; ?>

                <?php endif; ?>

            </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>