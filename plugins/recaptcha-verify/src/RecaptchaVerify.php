<?php

/**
 * reCAPTCHA Verify plugin for Craft CMS 3.x
 *
 * Verifies google reCAPTCHA.
 *
 * @link      http://nourl.io
 * @copyright Copyright (c) 2018 Karlis Melkis
 */

namespace recaptchaverify;


use Craft;
use craft\base\Plugin;
use craft\contactform\models\Submission;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Plugins;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use function class_exists;
use recaptchaverify\services\Recaptcha as RecaptchaService;
use recaptchaverify\variables\RecaptchaVerifyVariable;
use yii\base\Event;
use yii\base\ModelEvent;

/**
 * Class RecaptchaVerify
 *
 * @author    Karlis Melkis
 * @package   RecaptchaVerify
 * @since     0.0.1
 *
 */
class RecaptchaVerify extends Plugin
{
    public static $plugin;

    public $schemaVersion = '0.0.1';

    public $hasCpSettings = true;

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        $this->setComponents([
            'recaptcha' => RecaptchaService::class
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event)
            {
                $variable = $event->sender;

                $variable->set('recaptchaVerify', RecaptchaVerifyVariable::class);
            }
        );

        if ( class_exists('craft\\contactform\\models\\Submission') )
        {
            Event::on(
                \craft\contactform\models\Submission::class,
                \craft\contactform\models\Submission::EVENT_BEFORE_VALIDATE,
                function(ModelEvent $event)
                {
                    $submission = $event->sender;

                    $recaptchaToken = '';

                    if ( is_array($submission->message) )
                    {
                        $recaptchaToken = $submission->message['recaptchaToken'] ?? '';
                    }

                    if ( !(is_string($recaptchaToken) && $this->recaptcha->verifyToken($recaptchaToken)) )
                    {
                        $submission->addError('recaptchaToken', Craft::t('recaptcha-verify', 'Invalid reCAPTCHA.'));

                        $event->isValid = false;
                    }
                }
            );
        }
    }

    protected function createSettingsModel()
    {
        return new \recaptchaverify\models\Settings();
    }

    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('recaptcha-verify/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
