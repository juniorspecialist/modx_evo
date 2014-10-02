<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.09.14
 * Time: 9:46
 */

/*
 * контроллер для формирования меню админки
 */
class MenuController extends Controller{

    public $layout='//layouts/clm_content';

    public function actionIndex(){
        $this->render('index');
    }

    public function actionTree(){
        $this->layout = 'column1_modx';
        $this->render('tree');
    }
} 