<?php namespace Publipresse\Mailjet\Components;

use Validator;
use ValidationException;
use ApplicationException;
use Log;
use Cms\Classes\ComponentBase;
use Publipresse\Mailjet\Models\Settings;
use \Mailjet\Resources;

class SignupCheckbox extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Signup Checkbox',
            'description' => 'Sign up a new person by adding a checkbox to attach to another form'
        ];
    }

    public function defineProperties()
    {
        return [
            'email' => [
                'title' => 'Email field',
                'description' => 'The name of the email field in the form',
                'type' => 'string',
                'required' => true,
            ],
            'lists' => [
                'title' => 'Mailjet List IDs',
                'description' => 'Specify List IDs to subscribe the user (one per line)',
                'type' => 'stringList',
                'required' => true,
            ],
        ];
    }
    public function init() 
    {
        // Retrieve plugin settings
        $settings = Settings::instance();
        if (!$settings->api_key or !$settings->secret_key) {
            throw new ApplicationException('Mailjet API key is not configured.');
        }

        // Get email field value
        $data = post();
        $field = $this->property('email');
        $email = post($field);
        $checked = post($this->alias);

        if(empty($email) or empty($checked)) return;

        // Subscribe using Mailjet API
        $mj = new \Mailjet\Client($settings->api_key, $settings->secret_key);

        $body['Contacts'][] = [
            'Email' => $email,
        ];
        foreach($this->property('lists') as $list) {
            $body['ContactsLists'][] = [
                'ListID' => $list,
                'Action' => "addforce",
            ];
        }

        $response = $mj->post(Resources::$ContactManagemanycontacts, ['body' => $body]);
        if (!$response->success()) {
            Log::error('Could not subscribe user to newsletter', ['email' => $email]);
        }

    }

}
