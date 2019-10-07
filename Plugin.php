<?php namespace Publipresse\Mailjet;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'publipresse.mailjet::lang.plugin.name',
            'description' => 'publipresse.media::lang.plugin.description',
            'author'      => 'Publipresse',
            'icon'        => 'icon-envelope',
            'homepage'    => 'https://www.publipresse.fr'
        ];
    }

    public function registerComponents()
    {
        return [
            'Publipresse\Mailjet\Components\SignupForm' => 'signupForm',
            'Publipresse\Mailjet\Components\SignupCheckbox' => 'signupCheckbox',
        ];
    }

    /**
     * Registers administrator permissions for this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'publipresse.mailjet.configure' => [
                'tab'   => 'publipresse.mailjet::lang.plugin.name',
                'label' => 'publipresse.mailjet::lang.permissions.access_settings',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'publipresse.mailjet::lang.settings.title',
                'description' => 'publipresse.mailjet::lang.settings.description',
                'icon'        => 'icon-envelope',
                'class'       => 'Publipresse\Mailjet\Models\Settings',
                'order'       => 600,
                'permissions' => ['publipresse.mailjet.configure']
            ]
        ];
    }
}
