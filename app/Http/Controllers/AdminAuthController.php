<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends AuthController
{
    protected $authGuard = 'admin';
}
