<?php

use IMSGlobal\LTI\ToolProvider\User;
use izumi\yii2lti\ToolProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user User|null */
/* @var $result string */

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

    <?php if ($user !== null): ?>
        <p>Success! User ID by scopes:</p>
        <ul>
            <li>id only: <strong><?= $user->getId(ToolProvider::ID_SCOPE_ID_ONLY) ?></strong></li>
            <li>global: <strong><?= $user->getId(ToolProvider::ID_SCOPE_GLOBAL) ?></strong></li>
            <li>context: <strong><?= $user->getId(ToolProvider::ID_SCOPE_CONTEXT) ?></strong></li>
            <li>resource: <strong><?= $user->getId(ToolProvider::ID_SCOPE_RESOURCE) ?></strong></li>
        </ul>

        <h2>4. Send result back to consumer</h2>

        <?= Html::beginForm('', 'post', ['class' => 'form-inline']) ?>
        <form method="post" class="form-inline">
            <?= Html::input('number', 'result', $result, ['step' => '0.1', 'min' => '0', 'max' => 1, 'class' => 'form-control']) ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>

        <h2>5. Check Gradebook in Tool Consumer</h2>
    <?php endif ?>
</div>
