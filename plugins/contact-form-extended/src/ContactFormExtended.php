<?php
/**
 * Contact Form Extended plugin for Craft CMS 3.x
 *
 * Extending Contact Form Plugin features.
 *
 * @link      http://nourl.io
 * @copyright Copyright (c) 2018 Karlis Melkis
 */

namespace contactformextended;

use Craft;
use contactformextended\validators\GumpValidator;
use craft\base\Plugin;
use craft\contactform\Mailer;
use craft\contactform\events\SendEvent;
use craft\contactform\models\Submission;
use yii\base\Event;
use yii\base\ModelEvent;

/**
 * Class ContactFormExtended
 *
 * @author    Karlis Melkis
 * @package   ContactFormExtended
 * @since     0.0.1
 *
 */
class ContactFormExtended extends Plugin
{
    public static $plugin;

    public $schemaVersion = '0.0.1';

    public $hasCpSettings = false;

    protected $validator = null;

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        Event::on(Submission::class, Submission::EVENT_BEFORE_VALIDATE, function(ModelEvent $event)
        {
            $event = $this->getValidator()->check($event);
        });

        Event::on(Mailer::class, Mailer::EVENT_BEFORE_SEND, function(SendEvent $event)
        {
            $view = Craft::$app->getView();
            $oldTemplatesPath = $view->getTemplatesPath();

            $view->setTemplatesPath(self::getInstance()->getBasePath() . '/templates');

            $variables = [];

            $variables['subject']   = $event->submission->subject;
            $variables['fromName']  = $event->submission->fromName;
            $variables['fromEmail'] = $event->submission->fromEmail;

            if ( is_array($event->submission->message) )
            {
                foreach ( $event->submission->message as $field => $value )
                {
                    $variables[$field] = $value;
                }
            }
            else
            {
                $variables['message'] = $submission->message;
            }

            $html = $view->renderTemplate('email', $variables);

            $event->message->setHtmlBody($html);

            $view->setTemplatesPath($oldTemplatesPath);
        });
    }

    protected function getValidator()
    {
        if ( $this->validator !== null )
        {
            return $this->validator;
        }
        
        return (new GumpValidator((new \GUMP), $this->getSettings()));
    }

    protected function createSettingsModel()
    {
        return new \contactformextended\models\Settings();
    }
}
