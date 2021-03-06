<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $todayTaskProvider yii\data\ActiveDataProvider */
/* @var $yesterdayTaskProvider yii\data\ActiveDataProvider */
/* @var $tomorrowTaskProvider yii\data\ActiveDataProvider */
/* @var $unclosedOldTaskProvider yii\data\ActiveDataProvider */

$this->title = 'Plans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="credits-wrapper col-xs-12 col-md-6">
            <p>
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <h4>Long term</h4>
            <?= GridView::widget([
                'layout' => "{items}\n{pager}",
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'name',
                    [
                        'label' => 'hours spent',
                        'value' => function ($model) {
                            $hours = 0;
                            /** @var \app\modules\time\models\Plan $model */
                            foreach ($model->timeTracks as $track) {
                                $sec = (new DateTime($track->stop))->getTimestamp();
                                $sec -= (new DateTime($track->start))->getTimestamp();

                                $hours += $sec * 1 / (60 * 60);
                            }
                            
                            return number_format($hours, 2);
                        }
                    ],
                    'completeness',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'controller' => 'plans',
                    ],
                ],
            ]); ?>
        </div>
        <div class="credits-wrapper col-xs-12 col-md-6">
            <p class="text-right">
                <?= Html::a('Create', ['tasks/create'], ['class' => 'btn btn-success']) ?>
            </p>
            <h4>Daily</h4>
            <h5>Today</h5>
            <?= GridView::widget([
                'dataProvider' => $todayTaskProvider,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'start',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model*/
                            return (new \DateTime($model->start))->format('H:i');
                        },
                    ],
                    'duration',
                    'name',
                    [
                        'attribute' => 'state',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model */
                            return \app\modules\time\models\Task::STATES[$model->state];
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'controller' => 'tasks',
                    ],
                ],
            ]); ?>
            <h5>Tomorrow</h5>
            <?= GridView::widget([
                'dataProvider' => $tomorrowTaskProvider,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'start',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model*/
                            return (new \DateTime($model->start))->format('H:i');
                        },
                    ],
                    'duration',
                    'name',
                    [
                        'attribute' => 'state',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model */
                            return \app\modules\time\models\Task::STATES[$model->state];
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'controller' => 'tasks',
                    ],
                ],
            ]); ?>
            <h5>Yesterday</h5>
            <?= GridView::widget([
                'dataProvider' => $yesterdayTaskProvider,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'start',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model*/
                            return (new \DateTime($model->start))->format('H:i');
                        },
                    ],
                    'duration',
                    'name',
                    [
                        'attribute' => 'state',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model */
                            return \app\modules\time\models\Task::STATES[$model->state];
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'controller' => 'tasks',
                    ],
                ],
            ]); ?>
            <h5>Unclosed</h5>
            <?= GridView::widget([
                'dataProvider' => $unclosedOldTaskProvider,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'start',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model*/
                            return (new \DateTime($model->start))->format('d.m H:i');
                        },
                    ],
                    'duration',
                    'name',
                    [
                        'attribute' => 'state',
                        'value' => function ($model) {
                            /** @var \app\modules\time\models\Task $model */
                            return \app\modules\time\models\Task::STATES[$model->state];
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                        'controller' => 'tasks',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
