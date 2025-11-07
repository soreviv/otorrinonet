<?php

/**
 * --------------------------------------------------------------------------
 * Web Routes
 * --------------------------------------------------------------------------
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the Router class.
 *
 * The router can now handle controllers.
 * The syntax is 'ControllerName@methodName'.
 */

// Displays the home page.
$router->get('/', 'HomeController@index');

// Displays the services page.
$router->get('/servicios', 'ServicesController@index');

// Routes for scheduling an appointment.
$router->get('/agendar-cita', 'AppointmentController@create');
$router->post('/agendar-cita', 'AppointmentController@store');

// Routes for legal pages.
$router->get('/aviso-privacidad', 'LegalController@privacyPolicy');
$router->get('/politica-cookies', 'LegalController@cookiePolicy');
$router->get('/terminos-condiciones', 'LegalController@termsAndConditions');

// Routes for the contact form.
$router->get('/contacto', 'ContactController@create');
$router->post('/contacto', 'ContactController@store');

/**
 * --------------------------------------------------------------------------
 * Admin Panel Routes
 * --------------------------------------------------------------------------
 */
$router->get('/admin/login', 'AuthController@showLoginForm');
$router->post('/admin/login', 'AuthController@login');
$router->get('/admin/logout', 'AuthController@logout');
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/appointments', 'AdminController@listAppointments');
$router->post('/admin/appointments/status', 'AdminController@updateAppointmentStatus');
$router->get('/admin/messages', 'AdminController@listMessages');
$router->post('/admin/messages/status', 'AdminController@updateMessageStatus');

/**
 * --------------------------------------------------------------------------
 * API Routes
 * --------------------------------------------------------------------------
 */
$router->get('/api/available-times', 'ApiController@getAvailableTimes');