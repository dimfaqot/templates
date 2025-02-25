<?php

namespace App\Controllers;

class Home extends BaseController
{
    function __construct()
    {
        helper('functions');
        if (!user()) {
            gagal(base_url(), "Kamu belum login!.");
        }
        menu();
    }

    public function index(): string
    {
        return view('home', ['judul' => "Home"]);
    }

    public function delete()
    {
        $tabel = clear($this->request->getVar('tabel'));
        $id = clear($this->request->getVar('id'));

        $db = db($tabel);
        $q = $db->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal_js("Id tidak ditemukan!.");
        }

        $db->where('id', $id);
        if ($db->delete()) {
            sukses_js("Delete data sukses.");
        } else {
            sukses_js("Delete data gagal!.");
        }
    }
}
