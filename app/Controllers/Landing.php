<?php

namespace App\Controllers;

class Landing extends BaseController
{
    function __construct()
    {
        helper('functions');
        if (user()) {
            header("Location: " . base_url('home'));
            die;
        }
    }
    public function index(): string
    {
        return view('landing', ['judul' => "Landing"]);
    }
}
