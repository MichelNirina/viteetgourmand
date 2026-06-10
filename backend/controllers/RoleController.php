<?php

require_once __DIR__ . '/../models/Role.php';

class RoleController extends Controller
{
    public function index()
    {
        $this->requireRole(1);

        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        require_once __DIR__ . '/../views/role/index.php';
    }

    public function create()
    {
        $this->requireRole(1);

        require_once __DIR__ . '/../views/role/create.php';
    }

    public function store()
    {
        $this->requireRole(1);

        $libelle = $_POST['libelle'];

        $roleModel = new Role();
        $roleModel->createRole($libelle);

        header("Location: ?page=role");
        exit;
    }

    public function edit()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $roleModel = new Role();
        $role = $roleModel->getById($id);

        require_once __DIR__ . '/../views/role/edit.php';
    }

    public function update()
    {
        $this->requireRole(1);

        $id = $_POST['id'];
        $libelle = $_POST['libelle'];

        $roleModel = new Role();
        $roleModel->updateRole($id, $libelle);

        header("Location: ?page=role");
        exit;
    }

    public function delete()
    {
        $this->requireRole(1);

        $id = $_GET['id'];

        $roleModel = new Role();
        $roleModel->deleteRole($id);

        header("Location: ?page=role");
        exit;
    }
}