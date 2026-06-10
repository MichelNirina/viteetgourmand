<?php

require_once __DIR__ . '/../core/Controller.php';

class ContactController extends Controller
{
    public function index()
    {
        ob_start();
        require_once __DIR__ . '/../views/contact/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }

    public function send()
    {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $email = $_POST['email'];

        $_SESSION['success'] = "Votre demande a été envoyée.";

        header("Location: ?page=contact");
        exit;
    }
}