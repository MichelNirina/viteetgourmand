<?php
session_start();
include __DIR__ . '/config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur pour accéder à cette page.");
}

$successMsg = "";
$errorMsg = "";

// TRAITEMENT DES FORMULAIRES
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // AJOUTER MENU
    if ($action === 'ajouter_menu') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $theme = $_POST['theme'];
        $regime = $_POST['regime'];
        $nombre_personnes_min = $_POST['nombre_personnes_min'];
        $prix = $_POST['prix'];
        $stock = $_POST['stock'];
        $conditions = $_POST['conditions'];
        $allergenes = $_POST['allergenes'] ?? '';

        // Gestion image
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'uploads/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        $stmt = $pdo->prepare("INSERT INTO menus 
            (titre, description, image, theme, regime, nombre_personnes_min, prix, stock, conditions, allergenes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $image, $theme, $regime, $nombre_personnes_min, $prix, $stock, $conditions, $allergenes]);

        $successMsg = "Menu ajouté avec succès !";
    }

    // MODIFIER MENU
    if ($action === 'modifier_menu') {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $theme = $_POST['theme'];
        $regime = $_POST['regime'];
        $nombre_personnes_min = $_POST['nombre_personnes_min'];
        $prix = $_POST['prix'];
        $stock = $_POST['stock'];
        $conditions = $_POST['conditions'];
        $allergenes = $_POST['allergenes'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'uploads/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
            $stmt = $pdo->prepare("UPDATE menus SET titre=?, description=?, image=?, theme=?, regime=?, nombre_personnes_min=?, prix=?, stock=?, conditions=?, allergenes=? WHERE id=?");
            $stmt->execute([$titre, $description, $image, $theme, $regime, $nombre_personnes_min, $prix, $stock, $conditions, $allergenes, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE menus SET titre=?, description=?, theme=?, regime=?, nombre_personnes_min=?, prix=?, stock=?, conditions=?, allergenes=? WHERE id=?");
            $stmt->execute([$titre, $description, $theme, $regime, $nombre_personnes_min, $prix, $stock, $conditions, $allergenes, $id]);
        }

        $successMsg = "Menu modifié avec succès !";
    }

    // SUPPRIMER MENU
    if ($action === 'supprimer_menu') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM menus WHERE id=?");
        $stmt->execute([$id]);
        $successMsg = "Menu supprimé avec succès !";
    }
}

// Récupérer les menus après modification
$menus = $pdo->query("SELECT * FROM menus ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin - Gestion des Menus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .form-section { margin-bottom: 30px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; }
    .btn-delete { color: white; background-color: red; }
    .btn-delete:hover { background-color: darkred; }
    .card-img-top { height: 150px; object-fit: cover; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">Vite & Gourmand - Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h2>Gestion des Menus</h2>

    <?php if($successMsg): ?>
        <div class="alert alert-success"><?= $successMsg ?></div>
    <?php endif; ?>

    <!-- Formulaire Ajouter Menu -->
    <div class="form-section">
        <h4>Ajouter un nouveau menu</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="ajouter_menu">
            <div class="mb-2">
                <label>Titre :</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Description :</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-2">
                <label>Image :</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="mb-2 row">
                <div class="col">
                    <label>Thème :</label>
                    <select name="theme" class="form-select" required>
                        <option value="Classique">Classique</option>
                        <option value="Noel">Noël</option>
                        <option value="Paques">Pâques</option>
                        <option value="Evenement">Événement</option>
                    </select>
                </div>
                <div class="col">
                    <label>Régime :</label>
                    <select name="regime" class="form-select" required>
                        <option value="Classique">Classique</option>
                        <option value="Vegetarien">Végétarien</option>
                        <option value="Vegan">Vegan</option>
                    </select>
                </div>
            </div>
            <div class="mb-2 row">
                <div class="col">
                    <label>Nombre de personnes min :</label>
                    <input type="number" name="nombre_personnes_min" class="form-control" required>
                </div>
                <div class="col">
                    <label>Prix :</label>
                    <input type="number" step="0.01" name="prix" class="form-control" required>
                </div>
                <div class="col">
                    <label>Stock :</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
            </div>
            <div class="mb-2">
                <label>Conditions :</label>
                <textarea name="conditions" class="form-control"></textarea>
            </div>
            <div class="mb-2">
                <label>Allergènes :</label>
                <input type="text" name="allergenes" class="form-control" placeholder="ex: gluten, œufs, lactose">
            </div>
            <button type="submit" class="btn btn-success">Ajouter Menu</button>
        </form>
    </div>

    <hr>

    <!-- Affichage des menus existants -->
    <?php foreach ($menus as $menu): ?>
        <div class="form-section">
            <h5>Menu : <?= htmlspecialchars($menu['titre']) ?></h5>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="modifier_menu">
                <input type="hidden" name="id" value="<?= $menu['id'] ?>">

                <div class="mb-2">
                    <label>Titre :</label>
                    <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($menu['titre']) ?>" required>
                </div>
                <div class="mb-2">
                    <label>Description :</label>
                    <textarea name="description" class="form-control" required><?= htmlspecialchars($menu['description']) ?></textarea>
                </div>
                <div class="mb-2">
                    <label>Image (laisser vide si inchangée) :</label>
                    <input type="file" name="image" class="form-control">
                    <?php if($menu['image']): ?>
                        <img src="<?= htmlspecialchars($menu['image']) ?>" class="img-thumbnail mt-2" width="150">
                    <?php endif; ?>
                </div>
                <div class="mb-2 row">
                    <div class="col">
                        <label>Thème :</label>
                        <select name="theme" class="form-select" required>
                            <option value="Classique" <?= $menu['theme']=='Classique'?'selected':'' ?>>Classique</option>
                            <option value="Noel" <?= $menu['theme']=='Noel'?'selected':'' ?>>Noël</option>
                            <option value="Paques" <?= $menu['theme']=='Paques'?'selected':'' ?>>Pâques</option>
                            <option value="Evenement" <?= $menu['theme']=='Evenement'?'selected':'' ?>>Événement</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Régime :</label>
                        <select name="regime" class="form-select" required>
                            <option value="Classique" <?= $menu['regime']=='Classique'?'selected':'' ?>>Classique</option>
                            <option value="Vegetarien" <?= $menu['regime']=='Vegetarien'?'selected':'' ?>>Végétarien</option>
                            <option value="Vegan" <?= $menu['regime']=='Vegan'?'selected':'' ?>>Vegan</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col">
                        <label>Nombre de personnes min :</label>
                        <input type="number" name="nombre_personnes_min" class="form-control" value="<?= $menu['nombre_personnes_min'] ?>" required>
                    </div>
                    <div class="col">
                        <label>Prix :</label>
                        <input type="number" step="0.01" name="prix" class="form-control" value="<?= $menu['prix'] ?>" required>
                    </div>
                    <div class="col">
                        <label>Stock :</label>
                        <input type="number" name="stock" class="form-control" value="<?= $menu['stock'] ?>" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label>Conditions :</label>
                    <textarea name="conditions" class="form-control"><?= htmlspecialchars($menu['conditions']) ?></textarea>
                </div>
                <div class="mb-2">
                    <label>Allergènes :</label>
                    <input type="text" name="allergenes" class="form-control" value="<?= htmlspecialchars($menu['allergenes']) ?>" placeholder="ex: gluten, œufs, lactose">
                </div>

                <button type="submit" class="btn btn-primary">Modifier Menu</button>
            </form>

            <form method="post" class="mt-1">
                <input type="hidden" name="action" value="supprimer_menu">
                <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                <button type="submit" class="btn btn-delete">Supprimer Menu</button>
            </form>
        </div>
    <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
