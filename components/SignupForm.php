<?php namespace Publipresse\Mailjet\Components;

use Validator;
use ValidationException;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Publipresse\Mailjet\Models\Settings;
use \Mailjet\Resources;

class SignupForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Signup Form',
            'description' => 'Sign up a new person to a mailing list by collecting it\'s email address.'
        ];
    }

    public function defineProperties()
    {
        return [
            'lists' => [
                'title' => 'Mailjet List IDs',
                'description' => 'Specify List IDs to subscribe the user (one per line)',
                'type' => 'stringList',
                'required' => true,
            ],
        ];
    }

    public function onSignup()
    {
        // Retrieve plugin settings
        $settings = Settings::instance();
        if (!$settings->api_key or !$settings->secret_key) {
            throw new ApplicationException('Mailjet API key is not configured.');
        }

        // Validate Input
        $data = post();
        $rules = [
            'email' => 'required|email|min:2|max:64',
        ];
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        // Subscribe using Mailjet API
        $mj = new \Mailjet\Client($settings->api_key, $settings->secret_key);

        $body['Contacts'][] = [
            'Email' => post('email'),
        ];
        foreach($this->property('lists') as $list) {
            $body['ContactsLists'][] = [
                'ListID' => $list,
                'Action' => "addforce",
            ];
        }

        $response = $mj->post(Resources::$ContactManagemanycontacts, ['body' => $body]);
        
        if ($response->success()) {
            $this->page['success'] = true;
        } else {
            $this->page['error'] = $response->getData()['ErrorMessage'];
        }

    }
}
