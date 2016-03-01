yii2-datepicker
===============

Datepicker active form field for yii2 framework

Dependencies
===============
https://github.com/Filsh/bootstrap-datepicker

Usage
===============

for active form
```php
<?= $form->field($model, 'datetime', [
    'class' => 'filsh\datepicker\ActiveField',
    'options' => ['class' => 'input-group'],
    'inputOptions' => [
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('datetime')
    ],
    'clientOptions' => [
        'format' => 'mm/dd/yyyy'
    ],
    'addon' => [
        'class' => 'input-group-addon',
        'content' => '<span class="flaticon-small58"></span>'
    ]
])->datepickerInput() ?>
```
