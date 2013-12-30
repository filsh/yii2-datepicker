<?php

namespace yii\datepicker;

/**
 * Description of DateTimePicker
 * @property type $name Description
 */
class DatePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/filsh/yii2-datepicker/yii/datepicker/assets';

    public $js = [
        'js/bootstrap-datepicker.js'
    ];
    
    public $css = [
        'css/datepicker.css'
    ];
    
    public $depends = ['yii\web\JqueryAsset'];
}