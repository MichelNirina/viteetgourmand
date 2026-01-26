<?php
session_start();
include "config.php";

// Vérifier si administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur.");
}

$message = "";

// --- AJOUT MENU ---
if (isset($_POST['ajouter'])) {
    $stmt = $pdo->prepare("INSERT INTO Menu (titre, description, prix_minimum, nb_personnes_min)
                           VALUES (?, ?, ?, ?)");
    if ($stmt->execute([
        $_POST['titre'],
        $_POST['description'],
        $_POST['prix_minimum'],
        $_POST['nb_personnes_min']
    ])) {
        $message = "✅ Menu ajouté avec succès.";
    }
}

// --- MODIFICATION MENU ---
if (isset($_POST['modifier'])) {
    $stmt = $pdo->prepare("UPDATE Menu 
                           SET titre=?, description=?, prix_minimum=?, nb_personnes_min=? 
                           WHERE id=?");
    if ($stmt->execute([
        $_POST['titre'],
        $_POST['description'],
        $_POST['prix_minimum'],
        $_POST['nb_personnes_min'],
        $_POST['id']
    ])) {
        $message = "✅ Menu modifié.";
    }
}

// --- SUPPRESSION MENU ---
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM Menu WHERE id=?");
    if ($stmt->execute([$_GET['supprimer']])) {
        $message = "🚮 Menu supprimé.";
    }
}

// Charger menus
$menus = $pdo->query("SELECT * FROM Menu ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">Vite & Gourmand - Admin</a>
        <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>
</nav>

<div class="container my-5">

    <h2 class="mb-4">Gestion des Menus</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <!-- FORMULAIRE AJOUT -->
    <div class="card p-4 mb-5 shadow-sm">
        <h4 class="mb-3">Ajouter un nouveau menu</h4>

        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Titre :</label>
                    <input type="text" name="titre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Prix minimum (€) :</label>
                    <input type="number" step="0.01" name="prix_minimum" class="form-control" required>
                </div>

                <div class="col-md-12">
                    <label>Description :</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="col-md-6">
                    <label>Nombre minimum de personnes :</label>
                    <input type="number" name="nb_personnes_min" class="form-control" required>
                </div>

                <div class="col-12">
                    <button class="btn btn-success mt-3" name="ajouter">Ajouter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- LISTE DES MENUS -->
    <h4 class="mb-3">Menus existants</h4>

    <?php if (empty($menus)): ?>
        <p>Aucun menu disponible.</p>

    <?php else: ?>
        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Prix (€)</th>
                    <th>Min. pers</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($menus as $m): ?>
                    <tr>
                        <form method="POST">
                            <td>
                                <?= $m['id'] ?>
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                            </td>

                            <td><input type="text" name="titre" value="<?= htmlspecialchars($m['titre']) ?>" class="form-control"></td>

                            <td><textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($m['description']) ?></textarea></td>

                            <td><input type="number" step="0.01" name="prix_minimum" value="<?= $m['prix_minimum'] ?>" class="form-control"></td>

                            <td><input type="number" name="nb_personnes_min" value="<?= $m['nb_personnes_min'] ?>" class="form-control"></td>

                            <td class="text-center">
                                <button name="modifier" class="btn btn-primary btn-sm w-100 mb-1">Modifier</button>

                                <a class="btn btn-danger btn-sm w-100"
                                   href="?supprimer=<?= $m['id'] ?>"
                                   onclick="return confirm('Supprimer ce menu ?');">
                                   Supprimer
                                </a>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

</body>
</html>
