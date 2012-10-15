<?php

Router::mapResources(array(
	'Api.containers',
	'Api.conainer_items'
), array(
	'id' => '[a-z0-9-]+'
));
Router::parseExtensions();

/**
 * Static pages:
 * /slug => template name
 */
$static_pages = array(
	'' => 'home',
	'about' => 'about',
	'features' => 'features',
	'terms' => 'terms',
	'privacy' => 'privacy'
);
foreach($static_pages as $slug => $page) {
	Router::connect('/'.$slug, array('controller' => 'pages', 'action' => 'display', $page));
}

// Authentication
Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/signup', array('controller' => 'users', 'action' => 'signup'));
Router::connect('/account', array('controller' => 'users', 'action' => 'account'));

// Application
Router::connect('/dashboard', array('controller' => 'containers', 'action' => 'dashboard'));
Router::connect('/forgot_login/*', array('controller' => 'users', 'action' => 'qr_login'));

// Fallback
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

// Route /api to documentation
Router::redirect('/api', 'https://github.com/boxmeup/Boxmeup/wiki/API-Documentation', array('status' => '302'));

require CAKE . 'Config' . DS . 'routes.php';