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


        if (preg_match('#^([\w-]+)#i', $pathInfo, $matches)) {

            $criteria = new EMongoCriteria(array(
                'condition' => array('alias'=>$matches[1]),
            ));
            $page = Content::model()->findOne($criteria);

            if ($page !== null) {
                $_GET['id'] = $page->getPrimaryKey();
                return 'site/index';
            }else{

            }
        }else{
            $_GET['id'] = 1;
            return 'site/index';
        }
        return false;
    }

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route == 'site/index') {
            if (!empty($params['id'])) {


                $criteria = new EMongoCriteria(array(
                    'condition' => array('id'=>$params['id']),
                ));
                $page = Content::model()->findOne($criteria);

                return $page->alias.'.html';

            }elseif(!empty($params['alias'])){
                return $params['alias'].'.html';
            }
        }
        return false;
    }
}