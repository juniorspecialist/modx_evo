<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.09.14
 * Time: 16:56
 */

class PageUrlRule extends CBaseUrlRule
{
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {

        if(!preg_match('#manager#i', $pathInfo)){
            if (preg_match('#^([\w-]+)#i', $pathInfo, $matches)) {

                $criteria = new EMongoCriteria(array(
                    'condition' => array('alias'=>$matches[1]),
                ));
                $page = Content::model()->findOne($criteria);

                if ($page !== null) {
                    $_GET['id'] = $page->_id;
                    return 'site/index';
                }else{

                }
            }else{
                $_GET['id'] = (int) Yii::app()->config->get('SYSTEM.MAIN_PAGE');;
                return 'site/index';
            }
        }else{
           return false;
        }
    }

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route == 'site/index') {
            if (!empty($params['id'])) {


                $criteria = new EMongoCriteria(array(
                    'condition' => array('id'=>$params['id']),
                ));
                $page = Content::model()->findOne($criteria);

                if(!empty($page->alias)){
                    return $page->alias.'.html';
                }else{
                    return '';
                }


            }elseif(!empty($params['alias'])){

                if(!empty($params['alias'])){
                    return $params['alias'].'.html';
                }else{
                    return '';
                }


            }
        }
        return false;
    }
}