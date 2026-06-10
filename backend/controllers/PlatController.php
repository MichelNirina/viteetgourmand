<?php

require_once __DIR__ . '/../models/Plat.php';
require_once __DIR__ . '/../models/Allergene.php';
require_once __DIR__ . '/../models/Menu.php';
require_once __DIR__ . '/../core/Controller.php';

class PlatController extends Controller
{
    public function index()
    {
        $platModel = new Plat();
        $plats = $platModel->getAll();

        ob_start();
        require_once __DIR__ . '/../views/plat/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function create()
    {
        $allergeneModel = new Allergene();
        $menuModel = new Menu();

        $allergenes = $allergeneModel->getAll();
        $menus = $menuModel->getAll();

        ob_start();
        require_once __DIR__ . '/../views/plat/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=plat");
            exit;
        }

        $titre = trim($_POST['titre'] ?? '');
        $menu_id = (int)($_POST['menu_id'] ?? 0);

        if ($menu_id <= 0) {
            $_SESSION['error'] = "Veuillez sélectionner un menu.";
            header("Location: ?page=plat&action=create");
            exit;
        }

        $menuModel = new Menu();
        $menuExiste = $menuModel->getById($menu_id);

        if (!$menuExiste) {
            $_SESSION['error'] = "Menu introuvable.";
            header("Location: ?page=plat&action=create");
            exit;
        }

        $photo = null;

        if (!empty($_FILES['photo']['name'])) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                $_SESSION['error'] = "Format image invalide.";
                header("Location: ?page=plat&action=create");
                exit;
            }

            $filename = time() . '_' . basename($_FILES['photo']['name']);

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                __DIR__ . '/../assets/images/plats/' . $filename
            );

            $photo = 'assets/images/plats/' . $filename;
        }

        $platModel = new Plat();
        $plat_id = $platModel->create($titre, $photo, $menu_id);

        if (!empty($_POST['allergenes']) && is_array($_POST['allergenes'])) {
            foreach ($_POST['allergenes'] as $idAllergene) {
                $platModel->addAllergene($plat_id, (int)$idAllergene);
            }
        }

        $_SESSION['success'] = "Plat ajouté avec succès";

        header("Location: ?page=plat");
        exit;
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: ?page=plat");
            exit;
        }

        $platModel = new Plat();
        $menuModel = new Menu();
        $allergeneModel = new Allergene();

        $plat = $platModel->getById($id);
        $menus = $menuModel->getAll();
        $allergenes = $allergeneModel->getAll();

        $platAllergenes = $platModel->getAllergenesByPlat($id);

        if (!$plat) {
            header("Location: ?page=plat");
            exit;
        }

        ob_start();
        require __DIR__ . '/../views/plat/edit.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=plat");
            exit;
        }

        $id = (int)($_POST['plat_id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = "ID invalide";
            header("Location: ?page=plat");
            exit;
        }

        $titre = trim($_POST['titre'] ?? '');
        $menu_id = (int)($_POST['menu_id'] ?? 0);

        if ($menu_id <= 0) {
            $_SESSION['error'] = "Menu invalide";
            header("Location: ?page=plat&action=edit&id=" . $id);
            exit;
        }

        $platModel = new Plat();
        $plat = $platModel->getById($id);

        if (!$plat) {
            $_SESSION['error'] = "Plat introuvable";
            header("Location: ?page=plat");
            exit;
        }

        $photo = $plat['photo'];

        if (!empty($_FILES['photo']['name'])) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                $_SESSION['error'] = "Format image invalide.";
                header("Location: ?page=plat&action=edit&id=" . $id);
                exit;
            }

            $filename = time() . '_' . basename($_FILES['photo']['name']);

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                __DIR__ . '/../assets/images/plats/' . $filename
            );

            $photo = 'assets/images/plats/' . $filename;
        }

        $platModel->update($id, $titre, $photo, $menu_id);

        $platModel->deleteAllergenes($id);

        if (!empty($_POST['allergenes']) && is_array($_POST['allergenes'])) {
            foreach ($_POST['allergenes'] as $a) {
                $platModel->addAllergene($id, (int)$a);
            }
        }

        $_SESSION['success'] = "Plat mis à jour avec succès";

        header("Location: ?page=plat");
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = "ID invalide";
            header("Location: ?page=plat");
            exit;
        }

        $platModel = new Plat();
        $platModel->delete($id);

        $_SESSION['success'] = "Plat supprimé avec succès";

        header("Location: ?page=plat");
        exit;
    }
}