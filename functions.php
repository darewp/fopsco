<?php
/* 
Template file: Functions.php
This file contains the core functions and classes for the theme.
Testing deployment and functionality.
*/
require get_template_directory() . '/includes/traits/Singleton.php';
require get_template_directory() . '/includes/classes/HooksManager.php';
require get_template_directory() . '/includes/classes/AssetLoader.php';
require get_template_directory() . '/includes/classes/SVGSupport.php';
require get_template_directory() . '/includes/classes/JoinMember.php';
require get_template_directory() . '/includes/classes/DareWPAuto.php';
require get_template_directory() . '/includes/classes/TrackVideo.php';
require get_template_directory() . '/includes/classes/CarbonFieldInit.php';

\Fopsco\Classes\HooksManager::get_instance();
\Fopsco\Classes\AssetLoader::get_instance();
\Fopsco\Classes\SVGSupport::get_instance();
\Fopsco\Classes\JoinMember::get_instance();
\Fopsco\Classes\DareWPAuto::get_instance();
\Fopsco\Classes\TrackVideo::get_instance();
\Fopsco\Classes\CarbonFieldInit::get_instance();