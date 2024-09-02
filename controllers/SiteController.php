<?php /** @noinspection PhpUnused */

namespace app\controllers;

use ceLTIc\LTI\Enum\ServiceAction;
use ceLTIc\LTI\Outcome;
use izumi\yii2lti\Module;
use izumi\yii2lti\ToolEvent;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     * @return string|Response
     */
    public function actionIndex(): Response|string
    {
        $userPk = Yii::$app->session->get('userPk');
        /* @var Module $module */
        $module = Yii::$app->getModule('lti');
        $user = $userPk !== null ? $module->findUserById($userPk) : null;

        if ($user !== null && Yii::$app->request->isPost) {
            $result = min(1, max(0, Yii::$app->request->post('result')));
            $outcome = new Outcome(strval($result));
            if ($module->doOutcomesService(ServiceAction::Write, $outcome, $user)) {
                Yii::$app->session->set('result', $outcome->getValue());
                Yii::$app->session->addFlash('success', 'Result sent successfully');
            } else {
                Yii::$app->session->addFlash('error', 'Result sent failed');
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
     * @param ToolEvent $event
     */
    public static function ltiLaunch(ToolEvent $event)
    {
        $tool = $event->sender;

        // $userPk can be used for user identity
        $user = $tool->userResult;
        $resourceLink = $user->getResourceLink();
        $userPk = $user->getRecordId();
        $isAdmin = $user->isStaff() || $user->isAdmin();

        Yii::$app->session->set('isAdmin', $isAdmin);
        Yii::$app->session->set('userPk', $userPk);
        if ($resourceLink->hasOutcomesService()) {
            $outcome = new Outcome();
            Module::getInstance()->doOutcomesService(ServiceAction::Read, $outcome, $user);
            Yii::$app->session->set('result', $outcome->getValue());
        }
        Yii::$app->controller->redirect(['/site/index']);

        $tool->ok = true;
        $event->handled = true;
    }

    /**
     * LTI error handler
     * @param ToolEvent $event
     * @throws BadRequestHttpException
     */
    public static function ltiError(ToolEvent $event)
    {
        $tool = $event->sender;
        $msg = $tool->message;
        if (!empty($tool->reason)) {
            Yii::error($tool->reason);
            if ($tool->debugMode) {
                $msg = $tool->reason;
            }
        }
        throw new BadRequestHttpException($msg);
    }
}
