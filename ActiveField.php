<?php

namespace yii\datepicker;

use \Yii;
use yii\helpers\Html;
use yii\helpers\Json;

class ActiveField extends \yii\widgets\ActiveField
{
    public $clientOptions;
    
    public $clientEvents;
    
    public $addon = ['class' => 'input-group-addon', 'type' => 'append'];
    
    public function init()
    {
        if(empty($this->clientOptions['language'])) {
            $language = str_replace('-', '_', strtolower(Yii::$app->language));
            if(strpos($language, '_') !== false) {
                $language = explode('_', $language)[0];
            }
            $this->clientOptions['language'] = $language;
        }
        
        parent::init();
    }
    
    public function datepickerInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        DatePickerAsset::register($this->form->getView());
        $this->registerScript($options);
        $this->registerEvent($options);
        
        parent::textInput($options);
        
        $content = isset($this->addon['content']) ? $this->addon['content'] : '';
        unset($this->addon['content']);
        $addonHtml = Html::tag('span', $content, $this->addon);
        if(isset($this->addon['type']) && $this->addon['type'] === 'prepend') {
            $this->parts['{input}'] = $addonHtml . $this->parts['{input}'];
        } else {
            $this->parts['{input}'] .= $addonHtml;
        }
        return $this;
    }
    
    protected function registerScript($options = [])
    {
        if (!empty($this->clientOptions)) {
            $configure = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            if (!array_key_exists('id', $options)) {
                $options['id'] = Html::getInputId($this->model, $this->attribute);
            }
            $js = "jQuery('#{$options['id']}').datepicker($configure);";
            $this->form->getView()->registerJs($js);
        }
    }

    protected function registerEvent($options = [])
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            if (!array_key_exists('id', $options)) {
                $options['id'] = Html::getInputId($this->model, $this->attribute);
            }
            foreach ($this->clientEvents as $event => $handle) {
                $js[] = "jQuery('#{$options["id"]}').on('$event', $handle);";
            }
            $this->form->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }
}