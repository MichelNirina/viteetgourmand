<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Commande.php';

class AdminController extends Controller
{
    public function index()
    {
        Session::start();
        $this->requireRole([1]);

        ob_start();
        require_once __DIR__ . '/../views/admin/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function employees()
    {
        Session::start();
        $this->requireRole([1]);

        $userModel = new User();
        $employees = $userModel->getEmployees();

        ob_start();
        require_once __DIR__ . '/../views/admin/employees.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function createEmployee()
    {
        Session::start();
        $this->requireRole([1]);

        ob_start();
        require_once __DIR__ . '/../views/admin/create_employee.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function storeEmployee()
    {
        Session::start();
        $this->requireRole([1]);

        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];
        $ville = $_POST['ville'];
        $pays = $_POST['pays'];
        $adresse = $_POST['adresse_postale'];
        $role_id = 2;

        $userModel = new User();

        $userModel->createUser(
            $email,
            $password,
            $prenom,
            $telephone,
            $ville,
            $pays,
            $adresse,
            $role_id
        );

        $_SESSION['success'] = "Employé créé avec succès";

        header("Location: ?page=admin&action=employees");
        exit;
    }

    public function deleteEmployee()
    {
        Session::start();
        $this->requireRole([1]);

        $id = $_GET['id'];

        $userModel = new User();
        $userModel->deleteUser($id);

        $_SESSION['success'] = "Employé supprimé avec succès";

        header("Location: ?page=admin&action=employees");
        exit;
    }

    public function stats()
    {
        Session::start();
        $this->requireRole([1]);

        $commandeModel = new Commande();

        $total = $commandeModel->countCommandes();
        $byMenu = $commandeModel->getStatsByMenu();
        $ca = $commandeModel->getCAByMenu();

        ob_start();
        require_once __DIR__ . '/../views/admin/stats.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }
}