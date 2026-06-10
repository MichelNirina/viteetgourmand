<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Plat.php';
require_once __DIR__ . '/../models/Allergene.php';
require_once __DIR__ . '/../models/Menu.php';

class PlatController extends ApiController
{
    public function index()
    {
        $this->requireRole([1, 2]);
        $this->json((new Plat())->getAll());
    }

    public function show()
    {
        $this->requireRole([1, 2]);
        $id   = (int)($_GET['id'] ?? 0);
        $plat = (new Plat())->getById($id);
        if (!$plat) $this->error('Plat introuvable', 404);
        $plat['allergenes'] = (new Plat())->getAllergenesByPlat($id);
        $this->json($plat);
    }

    public function store()
    {
        $this->requireRole([1, 2]);

        $titre   = trim($_POST['titre']   ?? '');
        $menu_id = (int)($_POST['menu_id'] ?? 0);

        if (!$titre || !$menu_id) {
            $this->error('Titre et menu obligatoires', 400);
        }

        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['photo']['type'], $allowed)) {
                $this->error('Format image invalide', 400);
            }
            $filename = time() . '_' . basename($_FILES['photo']['name']);
            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                __DIR__ . '/../../frontend/assets/images/plats/' . $filename
            );
            $photo = 'assets/images/plats/' . $filename;
        }

        $platModel = new Plat();
        $plat_id   = $platModel->create($titre, $photo, $menu_id);

        if (!empty($_POST['allergenes']) && is_array($_POST['allergenes'])) {
            foreach ($_POST['allergenes'] as $a) {
                $platModel->addAllergene($plat_id, (int)$a);
            }
        }

        $this->json(['message' => 'Plat ajouté'], 201);
    }

    public function update()
    {
        $this->requireRole([1, 2]);

        $id      = (int)($_POST['plat_id'] ?? 0);
        $titre   = trim($_POST['titre']    ?? '');
        $menu_id = (int)($_POST['menu_id'] ?? 0);

        if (!$id) $this->error('ID manquant', 400);

        $platModel = new Plat();
        $plat      = $platModel->getById($id);
        if (!$plat) $this->error('Plat introuvable', 404);

        $photo = $plat['photo'];
        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['photo']['type'], $allowed)) {
                $this->error('Format image invalide', 400);
            }
            $filename = time() . '_' . basename($_FILES['photo']['name']);
            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                __DIR__ . '/../../frontend/assets/images/plats/' . $filename
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

        $this->json(['message' => 'Plat mis à jour']);
    }

    public function delete()
    {
        $this->requireRole([1, 2]);
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) $this->error('ID manquant', 400);
        (new Plat())->delete($id);
        $this->json(['message' => 'Plat supprimé']);
    }
}
