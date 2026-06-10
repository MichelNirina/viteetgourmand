<h1>Gestion des avis</h1>

<table>
    <tr>
        <th>Note</th>
        <th>Description</th>
        <th>Utilisateur</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($avis as $a): ?>
        <tr>
            <td><?= $a['note']; ?></td>
            <td><?= $a['description']; ?></td>
            <td><?= $a['prenom']; ?></td>
            <td><?= $a['statut']; ?></td>
            <td>

                <a href="?page=avis&action=valider&id=<?= $a['avis_id']; ?>">
                    Valider
                </a>

                |

                <a href="?page=avis&action=refuser&id=<?= $a['avis_id']; ?>">
                    Refuser
                </a>

            </td>
        </tr>
    <?php endforeach; ?>
</table>