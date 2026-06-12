<?php

require_once __DIR__ . '/../core/ApiController.php';
require_once __DIR__ . '/../models/Theme.php';
require_once __DIR__ . '/../models/Regime.php';

class FiltersController extends ApiController
{
    public function index()
    {
        $themes  = array_values(array_unique(array_column((new Theme())->getAll(),  'libelle')));
        $regimes = array_values(array_unique(array_column((new Regime())->getAll(), 'libelle')));

        $this->json([
            'themes'  => $themes,
            'regimes' => $regimes,
        ]);
    }
}
