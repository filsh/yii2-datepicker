<?php

namespace yii\datepicker;

use \Yii;

/**
 * Description of DateTimePicker
 * @property type $name Description
 */
class DatePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower';

    public $enableLocale = true;
    
    public $js = [
        'bootstrap-datepicker/js/bootstrap-datepicker.js'
    ];
    
    public $css = [
        'bootstrap-datepicker/css/datepicker.css'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    
    public function init()
    {
        if(!empty($this->js) && $this->enableLocale) {
            $language = str_replace('-', '_', strtolower(Yii::$app->language));
            if(strpos($language, '_') !== false) {
                $language = explode('_', $language)[0];
            }
            $this->js[] = 'bootstrap-datepicker/js/locales/bootstrap-datepicker.'. $language .'.js';
        }
        
        parent::init();
    }
}