<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Landing::index');
$routes->get('/home', 'Home::index');
$routes->post('/home/delete', 'Home::delete');

// menu
$routes->get('/menu', 'Menu::index');
$routes->post('/menu/add', 'Menu::add');
$routes->post('/menu/update', 'Menu::update');

// options
$routes->get('/options', 'Options::index');
$routes->post('/options/add', 'Options::add');
$routes->post('/options/update', 'Options::update');
