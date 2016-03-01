<?php

namespace filsh\datepicker;

use \Yii;

/**
 * Description of DateTimePicker
 * @property type $name Description
 */
class DatePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/bootstrap-datepicker/dist';

    public $enableLocale = true;
    
    public $js = [
        'js/bootstrap-datepicker.min.js'
    ];
    
    public $css = [
        'css/bootstrap-datepicker3.min.css'
    ];
    
    public $depends = [
        \yii\web\JqueryAsset::class
    ];
    
    public function init()
    {
        if(!empty($this->js) && $this->enableLocale && ($language = $this->getLanguage()) !== null) {
            $this->js[] = 'js/locales/bootstrap-datepicker.'. $language .'.min.js';
        }
        
        parent::init();
    }
    
    protected function getLanguage()
    {
        $language = str_replace('-', '_', strtolower(Yii::$app->language));
        if(strpos($language, '_') !== false) {
            $language = explode('_', $language)[0];
        }
        if($language === 'en') {
            $language = null;
        }
        return $language;
    }
}