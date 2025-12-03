<?php
namespace Fopsco\Classes;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Fopsco\Traits\Singleton;

class CarbonFieldInit {
    use Singleton;

    public function __construct() {
        add_action('carbon_fields_register_fields', [$this, 'register_fields']);
    }

    public function register_fields() {
        // Committee Container
        Container::make('theme_options', 'Committee')
            ->add_fields([
                Field::make('complex', 'committee_members', 'Committee Members')
                    ->add_fields([
                        Field::make('text', 'full_name', 'Full Name'),
                        Field::make('text', 'role', 'Role'),
                        Field::make('image', 'profile_picture', 'Profile Picture')
                            ->set_help_text('Upload the member\'s profile image.'),
                    ])
                    ->set_layout('tabbed-horizontal'),
            ]);
    }
}