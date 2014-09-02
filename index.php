<?php
error_reporting(E_ALL);

ini_set('display_errors', true);

ini_set('error_reporting',  E_ALL);

ini_set("memory_limit", "512M");

ini_set('max_execution_time', '600');
//phpinfo(); die();

error_reporting(E_ALL);
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
Yii::createWebApplication($config)->run();
?>

<br>
Отработало за <?=sprintf('%0.5f',Yii::getLogger()->getExecutionTime())?>с.
 Скушано памяти: <?=round(memory_get_peak_usage()/(1024*1024),2)."MB";
?>