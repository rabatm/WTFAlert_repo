<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    /**
     * Le chemin de redirection après connexion.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';
}
