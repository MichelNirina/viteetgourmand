<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Session.php';

require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/Menu.php';

class EmployeeController extends Controller
{
    public function index()
    {
        Session::start();
        $this->requireRole(2);

        ob_start();
        require_once __DIR__ . '/../views/employee/dashboard.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function menu()
    {
        Session::start();
        $this->requireRole(2);

        $menuModel = new Menu();
        $menus = $menuModel->getAll();

        ob_start();
        require_once __DIR__ . '/../views/menu/gestion.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }
}