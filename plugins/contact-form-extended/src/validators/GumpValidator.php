<?php

namespace contactformextended\validators;

use \GUMP;

class GumpValidator
{
    protected $validator;

    protected $settings;

    public function __construct($validator, $settings)
    {
        $this->validator = $validator;

        $this->settings = $settings;
    }

    public function check($event)
    {
        foreach ( $this->settings->getFields() as $field => $name )
        {
            $this->validator->set_field_name($field, $name);
        };

        $submission = $event->sender;

        $fields = $this->getFields($submission);

        $this->validator->filter_rules($this->settings->getFieldFilters());

        $this->validator->validation_rules($this->settings->getFieldRules());

        $validated_data = $this->validator->run($fields);

        if ( $validated_data === false )
        {
            foreach ( $this->validator->get_errors_array() as $field => $error )
            {
                $submission->addError($this->getFieldName($field), $error);
            }

            $event->isValid = false;
        }
        else
        {
            if ( is_array($submission->message) )
            {
                foreach ( $submission->message as $field => $value )
                {
                    $submission->message[$field] = $validated_data[$field];
                }
            }
            else
            {
                $submission->message = $validated_data['message'];
            }

            $submission->subject   = $validated_data['subject'];
            $submission->fromName  = $validated_data['fromName'];
            $submission->fromEmail = $validated_data['fromEmail'];
        }

        return $event;
    }

    protected function getFields($submission)
    {
        $fields              = [];
        $fields['subject']   = $submission->subject;
        $fields['fromName']  = $submission->fromName;
        $fields['fromEmail'] = $submission->fromEmail;

        if ( is_array($submission->message) )
        {
            foreach ( $submission->message as $field => $value )
            {
                $fields[$field] = $value;
            }
        }
        else
        {
            $fields['message'] = $submission->message;
        }

        return $fields;
    }

    protected function getSettings()
    {
        return $this->settings;
    }

    protected function getFieldName($field)
    {
        if ( in_array($field, ['subject', 'fromName', 'fromEmail', 'message']) )
        {
            return $field;
        }

        return 'message_' . $field;
    }
}
