<?php namespace Publipresse\Mailjet\Models;

use October\Rain\Database\Model;

/**
 * Twitter settings model
 *
 * @package system
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'publipresse_mailjet_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules
     */
    public $rules = [
        'api_key' => 'required',
        'secret_key' => 'required',
    ];
}
