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
            ->set_visible_in_graphql(true)
            ->add_fields([
                Field::make('complex', 'committee_members', 'Committee Members')
                    ->set_visible_in_graphql(true)
                    ->add_fields([
                        Field::make('text', 'full_name', 'Full Name')
                            ->set_visible_in_graphql(true),

                        Field::make('text', 'role', 'Role')
                            ->set_visible_in_graphql(true),

                        Field::make('image', 'profile_picture', 'Profile Picture')
                            ->set_visible_in_graphql(true),
                    ])
                    ->set_layout('tabbed-horizontal'),
            ]);

        // Partners Container
        Container::make('theme_options', 'Partners')
            ->set_visible_in_graphql(true)
            ->add_fields([
                Field::make('complex', 'partners_logo', 'Partners Logo')
                    ->set_visible_in_graphql(true)
                    ->add_fields([
                        Field::make('image', 'logo', 'Logo')
                            ->set_visible_in_graphql(true),

                        Field::make('text', 'partner_name', 'Partner Name')
                            ->set_visible_in_graphql(true),
                    ])
                    ->set_layout('tabbed-horizontal'),
            ]);
        }
}