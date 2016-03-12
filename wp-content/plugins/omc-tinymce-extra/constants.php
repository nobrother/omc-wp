<?php

namespace omc_tinymce_extra;

// General
define( __NAMESPACE__ .'\NAME', 'omc_tinymce_extra' );
define( __NAMESPACE__ .'\LABEL', 'OMC TinyMCE Extra' );
define( __NAMESPACE__ .'\SETTINGS', NAME.'_settings' );

// Dir
define( __NAMESPACE__ .'\DIR', __DIR__.'/' );
define( __NAMESPACE__ .'\ASSETS_DIR', DIR.'assets/' );
define( __NAMESPACE__ .'\JS_DIR', ASSETS_DIR.'js/' );
define( __NAMESPACE__ .'\CSS_DIR', ASSETS_DIR.'css/' );
define( __NAMESPACE__ .'\ADMIN_DIR', 'admin/' );

// URL
define( __NAMESPACE__ .'\URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ .'\JS_URL', URL.'assets/js/' );
define( __NAMESPACE__ .'\CSS_URL', URL.'assets/css/' );