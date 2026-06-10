<?php

require_once __DIR__ . '/../models/Menu.php';

class ClientController extends Controller
{
    public function index()
    {
        $this->requireRole(3);

        $model = new Menu();
        $menus = $model->getAllMenus();

        ob_start();
        require_once __DIR__ . '/../views/client/dashboard.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }
}