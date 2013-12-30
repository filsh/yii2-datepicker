yii2-datepicker
===============

Using
===============

```php
use yii\datepicker\DatePicker;


<?= DatePicker::widget([
    'model' => $model,
    'attribute' => 'datetime',
    'options' => [
        'class' => 'form-control',
        'readonly' => false
    ],
    'inputGroupAddonOptions' => [
        'class' => 'input-group-addon',
        'addonContent' => '<span class="flaticon-small58"></span>'
    ],
    'clientOptions' => [
        'format' => 'mm/dd/yyyy'
    ]
]); ?>
```
or for active form
```php
<?= $form->field($model, 'datetime', [
    'class' => 'yii\datepicker\ActiveField',
    'options' => ['class' => 'input-group'],
    'inputOptions' => [
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('datetime')
    ],
    'clientOptions' => [
        'format' => 'mm/dd/yyyy'
    ],
    'addonOptions' => [
        'class' => 'input-group-addon',
        'content' => '<span class="flaticon-small58"></span>'
    ]
])->datepickerInput() ?>
```
