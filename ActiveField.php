<?php

namespace yii\datepicker;

use \Yii;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Renders an datepicker field.
 *
 * For example:
 *
 * ```php
 * $form->field($model, 'datetime', [
 *      'class' => 'yii\datepicker\ActiveField',
 *      'options' => ['class' => 'input-group'],
 *      'template' => "{label}\n{input}{addon}\n{error}\n{hint}",
 *      'parts' => [
 *          '{error}' => '',
 *          '{addon}' => '<span class="input-group-addon">
 *                            <span class="flaticon-small58"></span>
 *                        </span>'
 *      ],
 *      'inputOptions' => [
 *          'class' => 'form-control',
 *          'placeholder' => $model->getAttributeLabel('datetime')
 *      ]
 *  ])->datepickerInput([
 *     'clientOptions' => [
 *          'format' => 'dd.mm.yyyy',
 *          'beforeShowDay' => new JsExpression("function(date) {
 *              return date.valueOf() >= nowDate.valueOf();
 *          }")
 *      ],
 *      'clientEvents' => [
 *          'changeDate' => "function(e) {
 *              $(this).datepicker('hide');
 *          }"
 *      ],
 *      'addon' => [
 *          'class' => 'input-group-addon',
 *          'content' => '<span class="flaticon-small58"></span>'
 *      ]
 *  ])
 * ```
 */
class ActiveField extends \yii\widgets\ActiveField
{
    public function datepickerInput($options = [])
    {
        DatePickerAsset::register($this->form->getView());
        $this->registerScript(!empty($options['clientOptions']) ? $options['clientOptions'] : []);
        $this->registerEvent(!empty($options['clientEvents']) ? $options['clientEvents'] : []);
        
        return parent::textInput();
    }
    
    /**
     * Print input as Embedded/inline calendar
     * @param type $options
     */
    public function calendarInput($options = [])
    {
        $clientOptions = !empty($options['clientOptions']) ? $options['clientOptions'] : [];
        $clientEvents = !empty($options['clientEvents']) ? $options['clientEvents'] : [];
        
        if (!isset($options['id'])) {
            $options['id'] = $clientOptions['id'] = $clientEvents['id'] = Html::getInputId($this->model, $this->attribute) . '_inline';
        }
        $this->hint('', ['id' => $options['id'], 'tag' => 'div']);
        $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->inputOptions);
        
        DatePickerAsset::register($this->form->getView());
        $this->registerScript($clientOptions);
        $this->registerEvent($clientEvents);
        
        return $this;
    }
    
    protected function registerScript($options = [])
    {
        if(!isset($options['language'])) {
            $language = str_replace('-', '_', strtolower(Yii::$app->language));
            if(strpos($language, '_') !== false) {
                $language = explode('_', $language)[0];
            }
            $options['language'] = $language;
        }
        
        $configure = !empty($options) ? Json::encode($options) : '';
        if (!isset($options['id'])) {
            $options['id'] = Html::getInputId($this->model, $this->attribute);
        }
        $this->form->getView()->registerJs("jQuery('#{$options['id']}').datepicker($configure);");
    }

    protected function registerEvent($options = [])
    {
        if (!isset($options['id'])) {
            $id = Html::getInputId($this->model, $this->attribute);
        } else {
            $id = $options['id'];
            unset($options['id']);
        }
        foreach ($options as $event => $handle) {
            $this->form->getView()->registerJs("jQuery('#{$id}').on('$event', $handle);");
        }
    }
}