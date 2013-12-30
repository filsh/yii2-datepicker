<?php

namespace yii\datepicker;

/**
 * Description of DateTimePicker
 * @property type $name Description
 */
class DatePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/filsh/yii2-datepicker/yii/datepicker/assets';

    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->js[] = YII_DEBUG ? 'js/jquery.datepicker.js' : 'js/jquery.datepicker.min.js';
        $this->css[] = YII_DEBUG ? 'css/datepicker.css' : 'js/datepicker.min.css';
        
        parent::init();
    }
}