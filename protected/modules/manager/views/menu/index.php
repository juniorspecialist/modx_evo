<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 9:49
 */
?>
    <div id="treecontrol" style="display: none;"><a href="#">Collapse All</a> |<a href="#">Expand All</a></div>
<?php

$this->widget('CTreeView',array(
    'id'=>'menu-treeview',
    'url'=>array('/manager/tree/fillTree'),
    //'data'=>$dataTree,
    //'control'=>'#treecontrol',
    //'animated'=>'fast',
    'collapsed'=>true,
    'unique'=>false,
    //'persist'=>'cookie',
    'htmlOptions'=>array(
        'class'=>'filetree'
    )
));
?>