<?php

require_once __DIR__ . '/../core/ApiController.php';

class ContactController extends ApiController
{
    public function send()
    {
        $titre       = trim($_POST['titre']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $email       = trim($_POST['email']       ?? '');

        if (!$titre || !$description || !$email) {
            $this->error('Tous les champs sont obligatoires', 400);
        }

        // TODO: stocker en base ou envoyer un email
        $this->json(['message' => 'Votre message a bien été envoyé']);
    }
}
