<?php

require_once __DIR__ . '/../core/Controller.php';

class Router
{
    public function run()
    {
        $page   = $_GET['page']   ?? 'home';
        $action = $_GET['action'] ?? 'index';

        switch ($page) {

            // ── AUTH ──────────────────────────────────────────────────
            case 'auth':
                require_once __DIR__ . '/../controllers/AuthController.php';
                $c = new AuthController();
                if ($action === 'login')  $c->authenticate();
                elseif ($action === 'logout') $c->logout();
                else                          $c->me();
                break;

            case 'register':
                require_once __DIR__ . '/../controllers/RegisterController.php';
                (new RegisterController())->store();
                break;

            // ── PUBLIC ────────────────────────────────────────────────
            case 'home':
                require_once __DIR__ . '/../controllers/HomeController.php';
                (new HomeController())->index();
                break;

            case 'filters':
                require_once __DIR__ . '/../controllers/FiltersController.php';
                (new FiltersController())->index();
                break;

            case 'contact':
                require_once __DIR__ . '/../controllers/ContactController.php';
                (new ContactController())->send();
                break;

            case 'menu':
                require_once __DIR__ . '/../controllers/MenuController.php';
                $c = new MenuController();
                if ($action === 'show') $c->show();
                else                    $c->index();
                break;

            // ── CLIENT ────────────────────────────────────────────────
            case 'client':
                require_once __DIR__ . '/../controllers/ClientController.php';
                (new ClientController())->index();
                break;

            case 'commande':
                require_once __DIR__ . '/../controllers/CommandeController.php';
                $c = new CommandeController();
                if ($action === 'store')         $c->store();
                elseif ($action === 'my')        $c->my();
                elseif ($action === 'update')    $c->update();
                elseif ($action === 'delete')    $c->delete();
                elseif ($action === 'updateEmploye') $c->updateEmploye();
                else                             $c->index();
                break;

            case 'avis':
                require_once __DIR__ . '/../controllers/AvisController.php';
                $c = new AvisController();
                if ($action === 'store')   $c->store();
                elseif ($action === 'valider') $c->validerAvis();
                elseif ($action === 'refuser') $c->refuserAvis();
                elseif ($action === 'delete')  $c->delete();
                else                           $c->index();
                break;

            // ── EMPLOYÉ ───────────────────────────────────────────────
            case 'employee':
                require_once __DIR__ . '/../controllers/EmployeeController.php';
                $c = new EmployeeController();
                if ($action === 'commandes') $c->commandes();
                else                         $c->index();
                break;

            // ── ADMIN ─────────────────────────────────────────────────
            case 'admin':
                require_once __DIR__ . '/../controllers/AdminController.php';
                $c = new AdminController();
                if ($action === 'employees')       $c->employees();
                elseif ($action === 'storeEmployee')  $c->storeEmployee();
                elseif ($action === 'deleteEmployee') $c->deleteEmployee();
                elseif ($action === 'stats')       $c->stats();
                else                               $c->index();
                break;

            case 'gestion_menu':
                require_once __DIR__ . '/../controllers/GestionMenuController.php';
                $c = new GestionMenuController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                elseif ($action === 'show')   $c->show();
                else                          $c->index();
                break;

            case 'plat':
                require_once __DIR__ . '/../controllers/PlatController.php';
                $c = new PlatController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                elseif ($action === 'show')   $c->show();
                else                          $c->index();
                break;

            case 'horaire':
                require_once __DIR__ . '/../controllers/HoraireController.php';
                $c = new HoraireController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                else                          $c->index();
                break;

            case 'allergene':
                require_once __DIR__ . '/../controllers/AllergeneController.php';
                $c = new AllergeneController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'delete') $c->delete();
                else                          $c->index();
                break;

            case 'theme':
                require_once __DIR__ . '/../controllers/ThemeController.php';
                $c = new ThemeController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                else                          $c->index();
                break;

            case 'regime':
                require_once __DIR__ . '/../controllers/RegimeController.php';
                $c = new RegimeController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                else                          $c->index();
                break;

            case 'user':
                require_once __DIR__ . '/../controllers/UserController.php';
                $c = new UserController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'update') $c->update();
                elseif ($action === 'delete') $c->delete();
                elseif ($action === 'profile') $c->profile();
                else                          $c->index();
                break;

            case 'role':
                require_once __DIR__ . '/../controllers/RoleController.php';
                $c = new RoleController();
                if ($action === 'store')  $c->store();
                elseif ($action === 'delete') $c->delete();
                else                          $c->index();
                break;

            default:
                http_response_code(404);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => 'Route introuvable']);
                break;
        }
    }
}
