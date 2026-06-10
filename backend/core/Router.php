<?php

require_once __DIR__ . '/../core/Controller.php';

class Router {

    public function run() {

        // page demandée
        $page = $_GET['page'] ?? 'home';
        $action = $_GET['action'] ?? 'index';
        
        switch($page) {

            case 'filters':
                require_once __DIR__ . '/../controllers/FiltersController.php';
                $controller = new FiltersController();
                $controller->index();
                break;

            case 'home':
                require_once __DIR__ . "/../controllers/HomeController.php";
                $controller = new HomeController();
                $controller->index();
                break;

            case 'login':
                require_once __DIR__ . "/../controllers/AuthController.php";
                $controller = new AuthController();
                
                if ($action === 'auth') {
                    $controller->authenticate(); // traitement login
                } else {
                    $controller->login(); // affichage formulaire
                }
                break;
            
            case 'logout':
                session_start();
                session_destroy();

                header("Location: ?page=home");
                exit;
                break;
            
            case 'menu':

                require_once __DIR__ . "/../controllers/MenuController.php";

                $controller = new MenuController();

                if ($action === 'show') {

                    $controller->show();

                } else {

                    $controller->index();

                }

                break;

            case 'gestion_menu':

                require_once __DIR__ . "/../controllers/GestionMenuController.php";

                $controller = new GestionMenuController();

                if ($action === 'create') {

                    $controller->create();

                } elseif ($action === 'store') {

                    $controller->store();

                } elseif ($action === 'edit') {

                    $controller->edit();

                } elseif ($action === 'update') {

                    $controller->update();

                } elseif ($action === 'delete') {

                    $controller->delete();

                } else {

                    $controller->index();

                }

                break;

            case 'user':

                require_once __DIR__ . "/../controllers/UserController.php";
                
                $controller = new UserController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;
            
            case 'commande':

                require_once __DIR__ . '/../controllers/CommandeController.php';

                $controller = new CommandeController();

                if ($action === 'create') {

                    $controller->create();

                } elseif ($action === 'store') {

                    $controller->store();

                } elseif ($action === 'edit') {

                    $controller->edit();

                } elseif ($action === 'update') {

                    $controller->update();

                } elseif ($action === 'delete') {

                    $controller->delete();

                } elseif ($action === 'my') {

                    $controller->my();

                }

                // EMPLOYÉ
                elseif ($action === 'editEmploye') {

                    $controller->editEmploye();

                } elseif ($action === 'updateEmploye') {

                    $controller->updateEmploye();

                }

                else {

                    $controller->index();

                }

                break;
                        
            case 'role':
                require_once __DIR__ . '/../controllers/RoleController.php';

                $controller = new RoleController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;
                
            case 'regime':
                require_once __DIR__ . '/../controllers/RegimeController.php';

                $controller = new RegimeController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;    

            case 'theme':
                require_once __DIR__ . '/../controllers/ThemeController.php';

                $controller = new ThemeController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;
            
            case 'allergene':
                require_once __DIR__ . '/../controllers/AllergeneController.php';

                $controller = new AllergeneController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;

            case 'horaire':
                require_once __DIR__ . '/../controllers/HoraireController.php';

                $controller = new HoraireController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }
                break;

            case 'plat':
                require_once __DIR__ . '/../controllers/PlatController.php';

                $controller = new PlatController();

                if ($action === 'create') {
                    $controller->create();
                } elseif ($action === 'store') {
                    $controller->store();
                } elseif ($action === 'edit') {
                    $controller->edit();
                } elseif ($action === 'update') {
                    $controller->update();
                } elseif ($action === 'delete') {
                    $controller->delete();
                } else {
                    $controller->index();
                }

                break;

            case 'avis':

            require_once __DIR__ . '/../controllers/AvisController.php';

            $controller = new AvisController();

            if ($action === 'create') {
                $controller->create();

            } elseif ($action === 'store') {
                $controller->store();

            } elseif ($action === 'edit') {
                $controller->edit();

            } elseif ($action === 'update') {
                $controller->update();

            } elseif ($action === 'delete') {
                $controller->delete();

            } elseif ($action === 'valider') {
                $controller->validerAvis();

            } elseif ($action === 'refuser') {
                $controller->refuserAvis();

            } else {
                $controller->index();
            }

            break;
            
            case 'admin':

                require_once __DIR__ . '/../controllers/AdminController.php';

                $controller = new AdminController();

                if ($action === 'index') {
                    $controller->index();
                }

                elseif ($action === 'employees') {
                    $controller->employees();
                }

                elseif ($action === 'createEmployee') {
                    $controller->createEmployee();
                }

                elseif ($action === 'storeEmployee') {
                    $controller->storeEmployee();
                }

                elseif ($action === 'deleteEmployee') {
                    $controller->deleteEmployee();
                }

                elseif ($action === 'stats') {
                    $controller->stats();
                }

                else {
                    $controller->index();
                }

                break;

            case 'employee':

                require_once __DIR__ . '/../controllers/EmployeeController.php';

                $controller = new EmployeeController();

                if ($action === 'index') {

                    $controller->index();

                } elseif ($action === 'menu') {

                    $controller->menu();

                } elseif ($action === 'commande') {

                    $controller->commande();

                } elseif ($action === 'avis') {

                    $controller->avis();

                } else {

                    $controller->index();

                }

                break;

            case 'client':
                require_once __DIR__ . '/../controllers/ClientController.php';
                $controller = new ClientController();
                $controller->index();
                break;

            case 'mentions':
                require_once __DIR__ . '/../views/mentions/index.php';
                break;

            case 'cgv':
                require_once __DIR__ . '/../views/cgv/index.php';
                break;

            case 'register':
                require_once __DIR__ . '/../controllers/RegisterController.php';
                $controller = new RegisterController();

                if ($action === 'store') {
                    $controller->store();
                } else {
                    $controller->index();
                }
                break;

            case 'contact':

            require_once __DIR__ . '/../controllers/ContactController.php';

            $controller = new ContactController();

            if ($action === 'send') {
                $controller->send();
            } else {
                $controller->index();
            }

            break;

            default:
                echo "404 - Page introuvable";
                break;

        }
    }
}