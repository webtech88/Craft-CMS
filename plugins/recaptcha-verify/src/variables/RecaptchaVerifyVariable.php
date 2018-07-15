<?php

namespace recaptchaverify\variables;

use craft\web\twig\variables\CraftVariable;
use recaptchaverify\RecaptchaVerify;

/**
 * Class RecaptchaVerifyVariable
 *
 * @author    Karlis Melkis
 * @package   RecaptchaVerify
 * @since     0.0.1
 *
 */
class RecaptchaVerifyVariable extends CraftVariable
{
    public function getRecaptchaSiteKey(): string
    {
        return RecaptchaVerify::getInstance()->getSettings()->recaptchaSiteKey;
    }
}
