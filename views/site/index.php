<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $isLtiSession bool */

$this->title = 'LTI Tool Provider';
?>
<div class="site-index">

    <h1>Quick start</h1>

    <h2>1. Create consumer credentials</h2>

    <p>
        Click <?= Html::a('here', ['lti/consumer/index']) ?> and create consumer. Copy generated secret.
    </p>

    <h2>2. Configure Tool Consumer</h2>

    <p>
        Go to your tool consumer (or use <?= Html::a('emulator', 'http://lti.tools/saltire/tc', ['target' => '_blank']) ?>) and configure Tool Provider.
    </p>

    <div class="alert alert-info">
        <span class="glyphicon glyphicon-info-sign"></span>
        Message URL:
        <strong><?= Html::encode(\yii\helpers\Url::to(['lti/connect/index'], true)) ?></strong>
    </div>

    <h2>3. Connect</h2>

    <?php if ($isLtiSession): ?>
        <p>Success!</p>

        <h2>4. Send result back to consumer</h2>

        <?= Html::beginForm('', 'post', ['class' => 'form-inline']) ?>
        <form method="post" class="form-inline">
            <?= Html::input('number', 'result', '0', ['step' => '0.1', 'min' => '0', 'max' => 1, 'class' => 'form-control']) ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>

        <h2>5. Check Gradebook in Tool Consumer</h2>
    <?php endif ?>
</div>
