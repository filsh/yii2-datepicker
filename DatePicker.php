<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Nghia Nguyen <yiidevelop@hotmail.com>
 * @since 2.0
 */

namespace yii\datepicker;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;

class DatePicker extends InputWidget
{
    public $inputGroupOptions = ['class' => 'input-group'];
    
    public $inputGroupAddonOptions = ['class' => 'input-group-addon'];
    
    public $inputGroupAddonContent;
    
    public $clientOptions;
    
    public $clientEvents;
    
    public function init()
    {
        parent::init();
        if (!isset($this->options['readonly'])) {
            $this->options['readonly'] = true;
        }
        
        DatePickerAsset::register($this->getView());
        $this->registerScript();
        $this->registerEvent();
    }

    public function run()
    {
        echo Html::beginTag('div', $this->inputGroupOptions);
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
        $addonContent = isset($this->inputGroupAddonOptions['addonContent']) ? $this->inputGroupAddonOptions['addonContent'] : '';
        unset($this->inputGroupAddonOptions['addonContent']);
        echo Html::tag('span', $addonContent, $this->inputGroupAddonOptions);
        echo Html::endTag('div');
    }

    public function registerScript()
    {
        if (!empty($this->clientOptions)) {
            $configure = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            $js = "jQuery('#{$this->options["id"]}').datepicker($configure);";
            $this->getView()->registerJs($js);
        }
    }

    public function registerEvent()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $js[] = "jQuery('#{$this->options["id"]}').on('$event',$handle);";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }

}