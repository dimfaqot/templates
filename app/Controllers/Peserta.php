<?php

namespace App\Controllers;

class Peserta extends BaseController
{
    function __construct()
    {
        helper('functions');
        menu();
    }

    public function index(): string
    {
        return view('home', ['judul' => "Home"]);
    }
}
