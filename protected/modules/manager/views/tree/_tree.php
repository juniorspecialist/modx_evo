<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.09.14
 * Time: 8:54
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