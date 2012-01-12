<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Routes for the application.
 *
 * Usage of four special words is allowed:
 *   :controller translates to ([_a-z][_a-z0-9]*)
 *   :action     translates to ([_a-z][_a-z0-9]*)
 *   :id         translates to ([0-9]+)
 *   :extension  translates to (\.[a-z0-9]{2,4})
 */
return array(

	// Benchmark route
	'benchmark/([_a-z][_a-z0-9]+)' => array(
		'controller' => 'benchmark',
		'action'     => 'index',
		'name'       => '$1',
	),

	// Default route
	':controller{/:action{/:id}}{:extension}' => array(
		'controller' => '$1',
		'action'     => '$2',
		'id'         => '$3',
		'extension'  => '$4',
	),

); // End Routes
