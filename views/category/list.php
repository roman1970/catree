<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\grid\GridView;
use yii\helpers\Url;

AppAsset::register($this);
?>
<div class="col-sm-8 blog-main">
    <h1 class="page-header">Пункты меню</h1>
    <?php  //var_dump($articles); exit; ?>
    <?= GridView::widget([
        'dataProvider' => $cats,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'template' => '{delete} {update} ',
                'buttons' =>
                    [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to('delete?id='.$model->id), [
                                'title' => Yii::t('yii', 'Удалить'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to('update?id='.$model->id), [
                                'title' => Yii::t('yii', 'Редактировать'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]);
                        },
                        

                    ]
            ]
        ],
    ]); ?>
</div>
<div class="col-sm-3 col-sm-offset-1 blog-sidebar">

    <div class="sidebar-module">
        <h4>Пункт меню (категория)</h4>
        <ol class="list-unstyled">
            <li><a href="index">Дерево</a></li>
            <li><a href="add">Добавить</a></li>
        </ol>
    </div>

</div><!-- /.blog-sidebar -->