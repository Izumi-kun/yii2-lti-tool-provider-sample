<?php

namespace app\controllers;

use IMSGlobal\LTI\ToolProvider\Outcome;
use IMSGlobal\LTI\ToolProvider\ResourceLink;
use IMSGlobal\LTI\ToolProvider\User;
use izumi\yii2lti\Module;
use izumi\yii2lti\ToolProviderEvent;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userPk = Yii::$app->session->get('userPk');
        /* @var Module $module */
        $module = Yii::$app->getModule('lti');
        $user = $userPk !== null ? User::fromRecordId($userPk, $module->toolProvider->dataConnector) : null;
        if (!$user->getResourceLink()) {
            $user = null;
        }

        if ($user !== null && Yii::$app->request->isPost) {
            $result = min(1, max(0, Yii::$app->request->post('result')));
            $outcome = new Outcome(strval($result));
            if ($user->getResourceLink()->doOutcomesService(ResourceLink::EXT_WRITE, $outcome, $user)) {
                Yii::$app->session->set('result', $outcome->getValue());
                Yii::$app->session->addFlash('success', 'Result sent successfully');
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'user' => $user,
            'result' => Yii::$app->session->get('result', '0'),
        ]);
    }

    /**
     * basic-lti-launch-request handler
     * @param ToolProviderEvent $event
     */
    public static function ltiLaunch(ToolProviderEvent $event)
    {
        $tool = $event->sender;

        // $userPk can be used for user identity
        $userPk = $tool->user->getRecordId();
        $isAdmin = $tool->user->isStaff() || $tool->user->isAdmin();

        Yii::$app->session->set('isAdmin', $isAdmin);
        Yii::$app->session->set('userPk', $userPk);
        Yii::$app->controller->redirect(['/site/index']);

        $tool->ok = true;
    }

    /**
     * LTI error handler
     * @param ToolProviderEvent $event
     * @throws BadRequestHttpException
     */
    public static function ltiError(ToolProviderEvent $event)
    {
        $tool = $event->sender;
        $msg = $tool->message;
        if (!empty($tool->reason)) {
            Yii::error($tool->reason);
            if ($tool->isDebugMode()) {
                $msg = $tool->reason;
            }
        }
        throw new BadRequestHttpException($msg);
    }
}
