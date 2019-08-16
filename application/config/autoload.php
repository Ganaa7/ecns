<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );



	/*
 * | -------------------------------------------------------------------
 * | AUTO-LOADER
 * | -------------------------------------------------------------------
 * | This file specifies which systems should be loaded by default.
 * |
 * | In order to keep the framework as light-weight as possible only the
 * | absolute minimal resources are loaded by default. For example,
 * | the database is not connected to automatically since no assumption
 * | is made regarding whether you intend to use it. This file lets
 * | you globally define which systems you would like loaded with every
 * | request.
 * |
 * | -------------------------------------------------------------------
 * | Instructions
 * | -------------------------------------------------------------------
 * |
 * | These are the things you can load automatically:
 * |
 * | 1. Packages
 * | 2. Libraries
 * | 3. Helper files
 * | 4. Custom config files
 * | 5. Language files
 * | 6. Models
 * |
 */
	
/*
 * | -------------------------------------------------------------------
 * | Auto-load Packges
 * | -------------------------------------------------------------------
 * | Prototype:
 * |
 * | $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
 * |
 */

$autoload ['packages'] = array ();

/*
 * | -------------------------------------------------------------------
 * | Auto-load Libraries
 * | -------------------------------------------------------------------
 * | These are the classes located in the system/libraries folder
 * | or in your application/libraries folder.
 * |
 * | Prototype:
 * |
 * | $autoload['libraries'] = array('database', 'session', 'xmlrpc');
 */

$autoload ['libraries'] = array (

		'database',

		'session',

		'table',

		'config',

		'pagination',

	    'javascript',

	    'form_validation',

	    'grocery_CRUD'

);

/*
 * | -------------------------------------------------------------------
 * | Auto-load Helper Files
 * | -------------------------------------------------------------------
 * | Prototype:
 * |
 * | $autoload['helper'] = array('url', 'file');
 */

$autoload ['helper'] = array (

		'date',

		'url',

		'form',

		'cookie',

		'general_helper',

		'file',

		'download',
		
		'directory',

		'my_helper'
		 
);

/*
 * | -------------------------------------------------------------------
 * | Auto-load Config files
 * | -------------------------------------------------------------------
 * | Prototype:
 * |
 * | $autoload['config'] = array('config1', 'config2');
 * |
 * | NOTE: This item is intended for use ONLY if you have created custom
 * | config files. Otherwise, leave it blank.
 * |
 */

$autoload ['config'] = array ();

/*
 * | -------------------------------------------------------------------
 * | Auto-load Language files
 * | -------------------------------------------------------------------
 * | Prototype:
 * |
 * | $autoload['language'] = array('lang1', 'lang2');
 * |
 * | NOTE: Do not include the "_lang" part of your file. For example
 * | "codeigniter_lang.php" would be referenced as array('codeigniter');
 * |
 */

$autoload ['language'] = array ();

/*
 * | -------------------------------------------------------------------
 * | Auto-load Models
 * | -------------------------------------------------------------------
 * | Prototype:
 * |
 * | $autoload['model'] = array('model1', 'model2');
 * |
 */

$autoload ['model'] = array (

	'user_model', 
	
	'location_model', 
	
	'section_model',
	
	'equipment_model',
	
	 'employee_model',
	
	 'user_m', 

	 'main_model', 

	 'new_model',
	 
	 'my_model_old',

	 'alert_model',

	 'file_model',
	 
	 'wm_model',

	 'spare_model',

	 'wm_main'

	);


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */