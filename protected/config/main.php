<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Modx EVO',

	// preloading 'log' component
	'preload'=>array('log', 'config','debug',),
    // язык поумолчанию
    'sourceLanguage' => 'en_US',
    'language' => 'ru',
    'defaultController' => 'site/index',
	// autoloading model and component classes
	'import'=>array(
        'application.models.*',
        'application.modules.manager.controllers.*',
        'application.components.*',
        'application.extensions.*',
        'application.extensions.redactor.*',
        'application.extensions.MongoYii.*',
        'application.extensions.MongoYii.validators.*',
        'application.extensions.MongoYii.behaviors.*',
        'application.extensions.MongoYii.util.*',
        'bootstrap.widgets.*',
        'bootstrap.helpers.TbHtml',
        'bootstrap.helpers.TbArray',
        'bootstrap.behaviors.TbWidget',
	),

    // path aliases
    'aliases' => array(
        'bootstrap' => realpath(__DIR__ . '/../extensions/bootstrap'), // change this if necessary
    ),

	'modules'=>array(

        //админка
        'manager' => array(
            'defaultController' => 'login',
        ),

        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
        ),
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(

        'bootstrap' => array(
            'class' => 'bootstrap.components.TbApi',
        ),


        'config'=>array(
            'class'=>'Settings',
            'cache'=>3600,
        ),

        // установим некоторые значения - по умолчанию
        'widgetFactory'=>array(
            'widgets'=>array(
                'CLinkPager'=>array(
                    'maxButtonCount'=>5,
                    //'cssFile'=>false,
                    'pageSize'=>100,

                ),
                'CJuiDatePicker'=>array(
                    'language'=>'ru',
                ),


            ),
        ),

//        'cache'=>array(
//            'class'=>'CMemCache',
//            'useMemcached'=>true,
//            'servers'=>array(
//                array(
//                    'host'=>'localhost',
//                    'port'=>11211,
//                    'weight'=>60,
//                ),
//            ),
//        ),

        'cache' => array(
            'class' => 'system.caching.CFileCache'
        ),

//        'debug' => array(
//            'class' => 'ext.yii2-debug.Yii2Debug',
//            'panels' => array(
//                'db' => array(
//                    // Disable code highlighting.
//                    'highlightCode' => false,
//                    // Disable substitution of placeholders with values in SQL queries.
//                    'insertParamValues' => false,
//                ),
//            ),
//        ),

        'mongodb' => array(
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'modx',
            //'mongoConnectionId' => 'mongodb',
            'enableProfiling'=>true,
        ),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'loginUrl'=>array('/manager/login/login')

		),
		// uncomment the following to enable URLs in path-format

        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(


//                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>/<id>',
//                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',

                // стандартное правило для обработки '/' как 'site/index'
//                array('class'=>'PageUrlRule'),'<controller:(site)\w+>' => '<controller>/index', 'urlSuffix' => '.html',
//                '' => 'site/index',



                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            'showScriptName'=>false,
            //'useStrictParsing' => true,
        ),
            /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database


		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=theservice_1',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),


				// uncomment the following to show log messages on web pages
//
//				array(
//					'class'=>'CWebLogRoute',
//				),

			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);