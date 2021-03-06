<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\wallet\models\Credit;
use app\modules\wallet\helpers\Wallet;

/* @var $this yii\web\View */
/* @var $operationsProvider yii\data\ActiveDataProvider */
/* @var $creditsProvider yii\data\ActiveDataProvider */
/* @var $dailyProvider yii\data\ActiveDataProvider */
/* @var $sumOfCredits float */
/* @var $weekTotal float */
/* @var $monthTotal float */
/* @var $expectedMonthIncome float */
/* @var $budgetExpenses array */

$this->title = 'Wallet';
$this->params['breadcrumbs'][] = $this->title;
$balanceEnoughForBudget = Wallet::isBalanceEnoughToCoverBudget();
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3>Balance: <?= number_format(Wallet::getBalance(), 2) ?></h3>

    <div class="container">
        <div class="row text-right">
            <div class="col-xs-8 text-left">
                <div>
                    Currently:
                    <?php if ($balanceEnoughForBudget): ?>
                        <label class="label label-success">Enough money to cover budget expenses</label>
                    <?php endif; ?>
                    <label class="label label-danger">spent this week: <?= number_format($weekTotal, 2) ?></label>
                    <label class="label label-info">spent this month: <?= number_format($monthTotal, 2) ?></label>
                </div>
                <div>
                    Budgets for this month:
                    <br>
                    <?php $expectedMonthExpenses = 0; ?>
                    <?php foreach ($budgetExpenses as $expense) : ?>
                        <?php if ($expense['sum'] < 0) {$expectedMonthExpenses += $expense['sum'];} ?>
                        <label class="label label-<?php if ($expense['sum'] == 0) : ?>default<?php elseif ($expense['sum'] >= 0) : ?>danger<?php else: ?>success<?php endif;?>"><?= $expense['name'] ?>: <?= $expense['sum'] == 0 ? 0 : number_format(-$expense['sum'], 2) ?></label>
                    <?php endforeach; ?>
                </div>
                <div>
                    <label class="label label-success">expected income: <?= number_format($expectedMonthIncome, 2) ?></label>
                    <label class="label label-primary">expected expenses: <?= number_format($expectedMonthExpenses, 2) ?></label>
                </div>
            </div>
            <div class="col-xs-4 text-right">
                <p>
                    <?= Html::a('Budget', ['/budget'], ['class' => 'btn btn-default']) ?>
                    <?= Html::a('Stat by tags', ['stat'], ['class' => 'btn btn-default']) ?> <?= Html::a('Create Operation', ['operations/create'], ['class' => 'btn btn-success']) ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="credits-wrapper col-xs-12 col-md-6">
                <h4>Debts</h4>
                <?= GridView::widget([
                    'dataProvider' => $creditsProvider,
                    'rowOptions'=> function($model) {
                        /** @var Credit $model */
                        $due = new DateTime($model['dueDate']);
                        $now = new DateTime();

                        if (($due > $now) && ($now->diff($due)->days < Credit::WARNING_NUM_DAYS)) {
                            return ['class' => 'warning'];
                        } elseif ($now >= $due) {
                            return ['class' => 'danger'];
                        }

                        return null;
                    },
                    'showFooter' => true,
                    'footerRowOptions' => ['class' => 'active'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'creditor',
                            'footer' => 'Total:',
                        ],
                        [
                            'attribute' => 'sum',
                            'format' => 'decimal',
                            'footer' => number_format($sumOfCredits),
                        ],
                        [
                            'attribute' => 'dueDate',
                            'format' => ['dateTime', 'php:d.m.Y']
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'controller' => 'credit',
                            /*'buttons' => [
                                'update' => function ($url, $model, $key) {

                                    $url = Yii::$app->urlManager->createUrl([
                                        '/wallet/operations/update',
                                        'id' => $model['operationId'],
                                    ]);
                                    $options = array_merge([
                                        'title' => Yii::t('yii', 'Update'),
                                        'aria-label' => Yii::t('yii', 'Update'),
                                        'data-pjax' => '0',
                                    ]);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                                },
                            ],*/
                        ],
                    ],
                ]); ?>
                <h4>Daily expenses</h4>
                <?= GridView::widget([
                    'dataProvider' => $dailyProvider,
                    'showFooter' => false,
                    'footerRowOptions' => ['class' => 'active'],
                    'columns' => [
                        [
                            'label' => 'Date',
                            'attribute' => 'id',
                            'format' => ['dateTime', 'php:d.m.Y']
                        ],
                        [
                            'attribute' => 'total',
                            'format' => 'decimal',
                            'contentOptions' => ['style' => 'color: red;'],
                        ],
                    ],
                ]); ?>
            </div>
            <div class="operations-wrapper col-xs-12 col-md-6">
                <h4>History</h4>
                <?= GridView::widget([
                    'dataProvider' => $operationsProvider,
                    'rowOptions'=>function($model){
                        if ($model['returned'] === '0') {//can be the null
                            return ['class' => 'danger'];
                        }
                        if ($model['isSalary']) {
                            return ['class' => 'success'];
                        }
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'sum',
                            'contentOptions' => function ($model) {

                                $color = $model['sum'] < 0 ? 'red' : 'green';
                                return ['style' => 'color: ' . $color . ';'];
                            },
                            'format' => 'decimal',
                        ],
                        [
                            'attribute' => 'description',
                            'contentOptions' => function ($model) {

                                $color = $model['budgetId'] ? 'inherit' : '#ff7f50';
                                return ['style' => 'color: ' . $color . ';', 'title' => 'Out of budget!'];
                            },

                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => ['dateTime', 'php:d.m.Y']
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update}',
                            'controller' => 'operations',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
