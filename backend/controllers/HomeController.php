<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Avis.php';

class HomeController extends ApiController
{
    public function index()
    {
        $avis = (new Avis())->getValidatedAvis();
        $this->json($avis);
    }
}
