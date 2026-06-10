<?php

require_once __DIR__ . '/../models/Avis.php';

class HomeController extends Controller
{
    public function index()
    {
        $avisModel = new Avis();

        $avis = $avisModel->getValidatedAvis();

        ob_start();
        require_once __DIR__ . '/../views/home/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layout.php';
    }
    
}