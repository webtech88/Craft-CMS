<?php

/**
 * reCAPTCHA Verify plugin for Craft CMS 3.x
 *
 * Verifies google reCAPTCHA.
 *
 * @link      http://nourl.io
 * @copyright Copyright (c) 2018 Karlis Melkis
 */

namespace recaptchaverify\services;

use Craft;
use ReCaptcha\ReCaptcha as ReCaptchaProvider;
use recaptchaverify\RecaptchaVerify;
use craft\base\Component;

/**
 * Class Recaptcha
 *
 * @author    Karlis Melkis
 * @package   RecaptchaVerify
 * @since     0.0.1
 *
 */
class Recaptcha extends Component
{
    public function verifyToken(string $token): bool
    {
        $recaptchaSecretKey = RecaptchaVerify::$plugin->getSettings()->recaptchaSecretKey;

        $ip = Craft::$app->getRequest()->remoteIP;

        if ( !$recaptchaSecretKey )
        {
            Craft::warning(
                Craft::t(
                    'contact-form-extended',
                    'Secret is not configured. Recaptcha will not be validated without one.',
                    ['token' => $token]
                ),
                __METHOD__
            );

            return false;
        }

        $recaptcha = new ReCaptchaProvider($recaptchaSecretKey);

        $recaptchaVerification = $recaptcha->verify($token, $ip);

        if ( !$recaptchaVerification->isSuccess() )
        {
            Craft::error(
                Craft::t(
                    'contact-form-extended',
                    'Recaptcha verification failed: {reasons}. Please check the error code reference at {link}',
                    [
                        'reasons' => join(', ', $recaptchaVerification->getErrorCodes()),
                        'link'    => 'https://developers.google.com/recaptcha/docs/verify#error-code-reference'
                    ]
                ),
                __METHOD__
            );

            return false;
        }

        Craft::trace(
            Craft::t(
                'contact-form-extended',
                'Verified token {token}',
                ['token' => $token]
            ),
            __METHOD__
        );

        return true;
    }
}
