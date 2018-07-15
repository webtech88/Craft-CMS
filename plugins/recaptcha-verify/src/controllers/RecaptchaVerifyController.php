<?php

/**
 * reCAPTCHA Verify plugin for Craft CMS 3.x
 *
 * Verifies google reCAPTCHA.
 *
 * @link      http://nourl.io
 * @copyright Copyright (c) 2018 Karlis Melkis
 */

namespace recaptchaverify\controllers;

use Craft;
use recaptchaverify\RecaptchaVerify;
use craft\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class RecaptchaVerifyController
 *
 * @author    Karlis Melkis
 * @package   RecaptchaVerify
 * @since     0.0.1
 *
 */
class RecaptchaVerifyController extends Controller
{
    protected $allowAnonymous = ['index'];

    public function actionIndex(): Response
    {
        $this->requirePostRequest();

        $recaptchaToken = Craft::$app->getRequest()->getBodyParam('recaptchaToken');

        if ( !$recaptchaToken )
        {
            throw new BadRequestHttpException(Craft::t('recaptcha-verify', 'No recaptchaToken in request'));
        }

        $verification = RecaptchaVerify::getInstance()->recaptcha->verifyToken($recaptchaToken);

        return $this->asJson([
            'status' => ($verification ? 'success' : 'failed')
        ]);
    }
}
