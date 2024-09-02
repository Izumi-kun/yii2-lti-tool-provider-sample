<?php

use ceLTIc\LTI\Enum\IdScope;
use ceLTIc\LTI\UserResult;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user UserResult|null */
/* @var $result string */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <h1>Quick start</h1>

    <h2>1. Create Platform credentials</h2>

    <p>
        Click <?= Html::a('here', ['lti/platform/index']) ?> and create Platform credentials.
    </p>

    <h2>2. Prepare Platform</h2>

    <p>
        Go to your Platform (or use <?= Html::a('emulator', 'https://saltire.lti.app/platform', ['target' => '_blank']) ?>) and configure Tool.
    </p>

    <h2>3. Connect</h2>

    <?php if ($user !== null): ?>
        <p>Success! User ID by scopes:</p>
        <ul>
            <li>id only: <strong><?= $user->getId(IdScope::IdOnly) ?></strong></li>
            <li>platform: <strong><?= $user->getId(IdScope::Platform) ?></strong></li>
            <li>context: <strong><?= $user->getId(IdScope::Context) ?></strong></li>
            <li>resource: <strong><?= $user->getId(IdScope::Resource) ?></strong></li>
        </ul>

        <?php if ($user->getResourceLink()->hasResultService()): ?>
            <h2>4. Send result back to Platform</h2>

            <?= Html::beginForm('', 'post', ['class' => 'form-inline']) ?>
            <form method="post" class="form-inline">
                <?= Html::input('number', 'result', $result, ['step' => '0.1', 'min' => '0', 'max' => 1, 'class' => 'form-control']) ?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
            <?= Html::endForm() ?>

            <h2>5. Check Gradebook in Platform</h2>
        <?php endif ?>
    <?php endif ?>
</div>
