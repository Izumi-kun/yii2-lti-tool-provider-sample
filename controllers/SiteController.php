<?php

namespace app\controllers;

use IMSGlobal\LTI\ToolProvider\Outcome;
use IMSGlobal\LTI\ToolProvider\ResourceLink;
use IMSGlobal\LTI\ToolProvider\User;
use izumi\yii2lti\Module;
use Yii;
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
        $isLtiSession = Yii::$app->session->get('isLtiSession', false);

        if ($isLtiSession && Yii::$app->request->isPost) {
            /* @var Module $module */
            $module = Yii::$app->getModule('lti');
            $user = User::fromRecordId(Yii::$app->session->get('userPk'), $module->toolProvider->dataConnector);

            $result = min(1, max(0, Yii::$app->request->post('result')));
            $outcome = new Outcome(strval($result));
            if ($user->getResourceLink()->doOutcomesService(ResourceLink::EXT_WRITE, $outcome, $user)) {
                Yii::$app->session->addFlash('success', 'Result sent successfully');
            }
        }

        return $this->render('index', [
            'isLtiSession' => $isLtiSession,
        ]);
    }
}
