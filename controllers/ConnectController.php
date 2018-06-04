<?php

namespace app\controllers;

use izumi\yii2lti\Module;
use izumi\yii2lti\ToolProviderEvent;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ConnectController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        /* @var Module $module */
        $module = Yii::$app->getModule('lti');

        // launch action
        $module->on(Module::EVENT_LAUNCH, function (ToolProviderEvent $event){
            $tool = $event->sender;

            // $userPk can be used for user identity
            $userPk = $tool->user->getRecordId();
            $isAdmin = $tool->user->isStaff() || $tool->user->isAdmin();

            Yii::$app->session->set('isAdmin', $isAdmin);
            Yii::$app->session->set('isLtiSession', true);
            Yii::$app->session->set('userPk', $userPk);

            $this->redirect(['site/index']);
            $tool->ok = true;
        });

        $module->on(Module::EVENT_ERROR, function (ToolProviderEvent $event){
            $tool = $event->sender;
            $msg = $tool->message;
            if (!empty($tool->reason)) {
                Yii::error($tool->reason);
                if ($tool->isDebugMode()) {
                    $msg = $tool->reason;
                }
            }
            throw new BadRequestHttpException($msg);
        });

        return $module->handleRequest();
    }
}
