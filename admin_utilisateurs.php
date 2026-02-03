<?php
session_start();
require "config.php";

// Vérifier si c'est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Vous devez être administrateur.");
}

// --- TRAITEMENT : Ajouter un utilisateur ---
$message = "";
if (isset($_POST['add_user'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role, date_creation)
                           VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role]);

    $message = "Utilisateur ajouté avec succès !";
}

// --- TRAITEMENT : Mise à jour du rôle ---
if (isset($_POST['update_role'])) {
    $id = $_POST['id_user'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE Utilisateur SET role = ? WHERE id = ?");
    $stmt->execute([$role, $id]);

    $message = "Rôle mis à jour.";
}

// --- TRAITEMENT : Suppression ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
    $stmt->execute([$id]);

    $message = "Utilisateur supprimé.";
}

// Charger tous les utilisateurs
$users = $pdo->query("SELECT * FROM Utilisateur ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar responsive -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Vite & Gourmand - Admin</a>
        <a class="btn btn-danger" href="deconnexion.php">Déconnexion</a>
    </div>
</nav>

<div class="container my-5">

    <h2 class="mb-4">Gestion des utilisateurs</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- AJOUTER UN UTILISATEUR -->
    <div class="card p-4 mb-4">
        <h4>Ajouter un utilisateur</h4>

        <form method="POST" class="row g-3 mt-2">
            <div class="col-12 col-md-6">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Email :</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Mot de passe :</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Rôle :</label>
                <select name="role" class="form-select">
                    <option value="utilisateur">Utilisateur</option>
                    <option value="employe">Employé</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" name="add_user" class="btn btn-primary w-100">Ajouter</button>
            </div>
        </form>
    </div>

    <!-- LISTE DES UTILISATEURS -->
    <h4>Liste des utilisateurs</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom & Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nom']." ".$u['prenom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>

                        <td>
                            <form method="POST" class="d-flex flex-wrap gap-1">
                                <input type="hidden" name="id_user" value="<?= $u['id'] ?>">

                                <select name="role" class="form-select form-select-sm">
                                    <option value="utilisateur" <?= $u['role']=="utilisateur"?"selected":"" ?>>Utilisateur</option>
                                    <option value="employe" <?= $u['role']=="employe"?"selected":"" ?>>Employé</option>
                                    <option value="admin" <?= $u['role']=="admin"?"selected":"" ?>>Admin</option>
                                </select>

                                <button type="submit" name="update_role" class="btn btn-sm btn-primary">OK</button>
                            </form>
                        </td>

                        <td><?= $u['date_creation'] ?></td>

                        <td>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="admin_utilisateurs.php?delete=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer cet utilisateur ?');">
                                    Supprimer
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Vous-même</span>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
