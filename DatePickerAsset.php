<?php

namespace yii\datepicker;

use \Yii;

/**
 * Description of DateTimePicker
 * @property type $name Description
 */
class DatePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/filsh/bootstrap-datepicker';

    public $js = [
        'js/bootstrap-datepicker.js'
    ];
    
    public $css = [
//        'css/datepicker.css'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    
    public function init()
    {
        $language = str_replace('-', '_', strtolower(Yii::$app->language));
        if(strpos($language, '_') !== false) {
            $language = explode('_', $language)[0];
        }
        $this->js[] = 'js/locales/bootstrap-datepicker.'. $language .'.js';
        
        parent::init();
    }
}