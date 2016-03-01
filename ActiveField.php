<?php

namespace filsh\datepicker;

use \Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Renders an datepicker field.
 *
 * For example:
 *
 * ```php
 * $form->field($model, 'datetime', [
 *      'class' => 'filsh\datepicker\ActiveField',
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
 *  ])
 * 
 * $form->field($model, 'date', [
 *      'class' => 'filsh\datepicker\ActiveField',
 *      'enableClientValidation' => false,
 *      'options' => ['class' => 'input-group calendar date'],
 *      'inputOptions' => [
 *          'class' => 'add-on'
 *      ]
 *  ])->calendarInput([
 *      'clientOptions' => [
 *          'format' => 'yyyy-mm-dd',
 *          'todayHighlight' => true,
 *          'beforeShowDay' => new JsExpression("(function() {
 *              var t = new Date();
 *              var now = new Date(t.getFullYear(), t.getMonth(), t.getDate(), 0, 0, 0, 0);
 *              return function(date) {
 *                  return date.valueOf() >= now.valueOf();
 *              };
 *          })()")
 *      ]
 *  ])
 * 
 *    $inputName = Html::getInputName($model, 'birthday');
 *    $inputOptions = [
 *        'class' => 'form-control input-sm',
 *        'disabled' => 'disabled'
 *    ];
 *    $months = [
 *        1 => P::t('common', 'January'),
 *        2 => P::t('common', 'February'),
 *        3 => P::t('common', 'March'),
 *        4 => P::t('common', 'April'),
 *        5 => P::t('common', 'May'),
 *        6 => P::t('common', 'June'),
 *        7 => P::t('common', 'July'),
 *        8 => P::t('common', 'August'),
 *        9 => P::t('common', 'September'),
 *        10 => P::t('common', 'October'),
 *        11 => P::t('common', 'November'),
 *        12 => P::t('common', 'December'),
 *    ];
 *    $years = [];
 *    for($i = date('Y') - MAX_AGE; $i < date('Y'); $i++) {
 *        $years[$i] = $i;
 *    }
 *
 *    echo $form->field($model, 'birthday', [
 *        'class' => 'filsh\datepicker\ActiveField',
 *        'options' => ['class' => 'form-group child-birthday'],
 *        'inputOptions' => $inputOptions
 *    ])->compositeInput([
 *        'inputWrapOptions' => [
 *            'container' => ['class' => 'row'],
 *            'options' => ['class' => 'col-sm-3'],
 *            'parts' => [
 *                '{month}' => Html::tag('div', Html::dropDownList($inputName . '[month]', null, $months, $inputOptions), ['class' => 'col-sm-5']),
 *                '{year}' => Html::tag('div', Html::dropDownList($inputName . '[year]', null, $years, $inputOptions), ['class' => 'col-sm-4'])
 *            ]
 *        ]
 *    ])
 * ```
 */
class ActiveField extends \yii\widgets\ActiveField
{
    public function datepickerInput($options = [])
    {
        DatePickerAsset::register($this->form->getView());
        $this->registerScript(isset($options['clientOptions']) ? $options['clientOptions'] : false);
        $this->registerEvent(isset($options['clientEvents']) ? $options['clientEvents'] : false);
        
        return $this;
    }
    
    /**
     * Print input as Embedded/inline calendar
     * @param type $options
     */
    public function calendarInput($options = [])
    {
        $clientOptions = !empty($options['clientOptions']) ? $options['clientOptions'] : [];
        $clientEvents = !empty($options['clientEvents']) ? $options['clientEvents'] : [];
        $options = !empty($options['options']) ? $options['options'] : [];
        
        if (!isset($options['id'])) {
            $options['id'] = $clientOptions['id'] = $clientEvents['id'] = Html::getInputId($this->model, $this->attribute) . '_inline';
        }
        
        $this->parts['{input}'] = Html::tag('div', Html::activeHiddenInput($this->model, $this->attribute, $this->inputOptions), $options);
        
        DatePickerAsset::register($this->form->getView());
        $this->registerScript($clientOptions);
        $this->registerEvent($clientEvents);
        
        return $this;
    }
    
    protected function registerScript($options = [])
    {
        if($options === false) {
            return;
        }
        
        if(!isset($options['language']) && ($language = $this->getLanguage()) !== null) {
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
        if($options === false) {
            return;
        }
        
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