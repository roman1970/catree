<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */


$this->title = $model->isNewRecord ? 'Добавить категорию' : 'Редактировать категорию';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    

    <div class="row">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-8">
            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'action' => \yii\helpers\Url::to('add')
            ]); ?>
            
            <?= $form->field($model, 'title')->textInput()  ?>
            <?= $form->field($model, 'rootCat', ['enableAjaxValidation' => true])->dropDownList(ArrayHelper::map(\app\models\Category::find()->all(),'id','title'),
                ['prompt' => 'Это корневая категория'])  ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => 'btn btn-primary', 'name' => 'category-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-2">
        </div>
    </div>
</div>