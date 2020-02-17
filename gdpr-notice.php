<?php
/**
 * Plugin Name: GDPR Notice
 * Plugin URI: https://moyses.me
 * Description: Cookie Plugin WordPress. Notice GDPR cookie
 * Version: 1.0.0
 * Author: Moyses dos Santos
 * Author URI: https://moyses.me
 * Author e-mail: moysesspj@gmail.com
 * Text Domain: gdpr-notice
 */

if(!defined('ABSPATH')) exit('No direct script access allowed');

global $theme;
global $message;
global $ok_button;

$theme = "ocean-bottom";
$message = __( 'We use cookies to provide our services and for analytics and marketing. To find out more about our use of cookies, please see our Privacy Policy. By continuing to browse our website, you agree to our use of cookies.', 'cookie-consent' );
$ok_button = __( 'Aceito', 'cookie-consent' );


function gdprCookieScripts()
{
    if(!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php') {
        wp_register_script('cc-js', ''.plugins_url( 'assets/plugin-js/cookieconsent.latest.build.js', __FILE__ ).'', array(), true);
        wp_enqueue_script('cc-js');
    }

}
add_action('wp_enqueue_scripts', 'gdprCookieScripts');


function gdprCookieStyle() {
    $theme = get_option('gdpr_cc_theme');

    switch ($theme) {
        case "ocean-bottom":
            wp_register_style('cc-ocean-bottom', plugins_url('assets/plugin-css/ocean-bottom.css', __FILE__), array());
            wp_enqueue_style('cc-ocean-bottom');
            break;
        case "ocean-top":
            wp_register_style('cc-ocean-top', plugins_url('assets/plugin-css/ocean-top.css', __FILE__), array());
            wp_enqueue_style('cc-ocean-top');
            break;
        case "forest-bottom":
            wp_register_style('cc-forest-bottom', plugins_url('assets/plugin-css/forest-bottom.css', __FILE__), array());
            wp_enqueue_style('cc-forest-bottom');
            break;
        case "forest-top":
            wp_register_style('cc-forest-top', plugins_url('assets/plugin-css/forest-top.css', __FILE__), array());
            wp_enqueue_style('cc-forest-top');
            break;
        case "light-bottom":
            wp_register_style('cc-light-bottom', plugins_url('assets/plugin-css/light-bottom.css', __FILE__), array());
            wp_enqueue_style('cc-light-bottom');
            break;
        case "light-top":
            wp_register_style('cc-light-top', plugins_url('assets/plugin-css/light-top.css', __FILE__), array());
            wp_enqueue_style('cc-light-top');
            break;
        case "desabled-cookie":
            wp_register_style('cc-default-notice', plugins_url('assets/plugin-css/none.css', __FILE__), array());
            wp_enqueue_style('cc-default-notice');
            break;
        default:
            wp_register_style('cc-ocean-bottom', plugins_url('assets/plugin-css/ocean-bottom.css', __FILE__), array());
            wp_enqueue_style('cc-ocean-bottom');
    }
}
add_action('wp_enqueue_scripts', 'gdprCookieStyle');

/** Add CC config js if cookie.consent.js loaded */
function gdprCookieInlineScripts()
{ ?>
    <script>
        window.cookieconsent_options = {
            "message":"<?php if(get_option('gdpr_cc_text_headline')): echo esc_js(get_option('gdpr_cc_text_headline')); else: global $message; echo esc_js($message); endif; ?>",
            "dismiss":"<?php if(get_option('gdpr_cc_text_button')): echo esc_js(get_option('gdpr_cc_text_button')); else: global $ok_button; echo esc_js($ok_button); endif; ?>",
            "theme":"<?php if(get_option('gdpr_cc_theme')): echo esc_js(get_option('gdpr_cc_theme')); else: global $theme; echo esc_js($theme); endif; ?>"
        };
    </script>
    <?php
}
add_action('wp_footer', 'gdprCookieInlineScripts');

/** Add Settings Page */
add_action('admin_menu', 'gdprCookieSettings');
function gdprCookieSettings() {
    add_menu_page(
        __('GDPR Notice','cookie-consent'), 
        __('GDPR Notice','cookie-consent'), 
        'manage_options', 
        'cookie-consent', 
        'gdprCookieSettingsPage'
    );
}

/** option template for settings pages */
function gdprCustomOptionTemplate($option_section, $option_options) {
    ?>
        <div class="wrap">
            <h1>GDPR Notice </h1>
            <h2>Configuração da notificação</h2>
            
            <hr>
            
            <form class="cc" method="post" action="options.php" id="cookieConsentSettings">
                <?php
                    settings_fields($option_section);
                    do_settings_sections($option_options);
                    submit_button();
                ?>
            </form>
        </div>
    <?php
}

function gdprInputField($input, $placeholder) {
    echo '<input class="regular-text" type="text" name="'.$input.'" id="'.$input.'" value="'.get_option($input).'" placeholder="'.$placeholder.'" />';
}
function gdprTextArea($input, $placeholder) {
    echo '<textarea class="regular-textarea" name="'.$input.'" id="'.$input.'" placeholder="'.$placeholder.'" rows="4" cols="50">'.get_option($input).'</textarea>';
}

/** Plugin Settings Tab */
function gdprCookieSettingsPage() {
    $option_section = "gdpr-cc-plugin-section";
    $option_options = "gdpr-cc-plugin-options";
    gdprCustomOptionTemplate($option_section, $option_options);
}

/** Plugin Settings Fields */
function gdprCookieChooseTheme() {
    echo
        "<select name='gdpr_cc_theme' id='gdpr_cc_theme'>".
            "<option value='desabled-cookie' ".selected( get_option('gdpr_cc_theme'), 'ocean-bottom', false).">".__('Cookie Desativado', 'cookie-consent')."</option>".
            "<option value='ocean-bottom' ".selected( get_option('gdpr_cc_theme'), 'ocean-bottom', false).">".__('Ocean Bottom', 'cookie-consent')."</option>".
            "<option value='ocean-top' ".selected( get_option('gdpr_cc_theme'), 'ocean-top', false).">".__('Ocean Top', 'cookie-consent')."</option>".
            "<option value='light-bottom' ".selected( get_option('gdpr_cc_theme'), 'light-bottom', false).">".__('Light Bottom', 'cookie-consent')."</option>".
            "<option value='light-top' ".selected( get_option('gdpr_cc_theme'), 'light-top', false).">".__('Light Top', 'cookie-consent')."</option>".
            "<option value='forest-bottom' ".selected( get_option('gdpr_cc_theme'), 'forest-bottom', false).">".__('Forest Bottom', 'cookie-consent')."</option>".
            "<option value='forest-top' ".selected( get_option('gdpr_cc_theme'), 'forest-top', false).">".__('Forest Top', 'cookie-consent')."</option>".
        "</select>";
}

function gdprCookieTextHeadline() {
    $input = "gdpr_cc_text_headline";
    $placeholder = "We use cookies to provide our services and for analytics and marketing. To find out more about our use of cookies, please see our Privacy Policy. By continuing to browse our website, you agree to our use of cookies";
    gdprTextArea($input, $placeholder);
}

function gdprCookieTextAcceptButton() {
    $input = "gdpr_cc_text_button";
    $placeholder = "Aceito";
    gdprInputField($input, $placeholder);
}

/**
 * Save and get options
 */
function gdprCookieFields() {
    add_settings_section("gdpr-cc-plugin-section", null, null, "gdpr-cc-plugin-options");
    
    add_settings_field("gdpr_cc_theme", __('Tema da notificação', 'cookie-consent'), "gdprCookieChooseTheme", "gdpr-cc-plugin-options", "gdpr-cc-plugin-section");
    add_settings_field("gdpr_cc_text_headline", __('Descrição', 'cookie-consent'), "gdprCookieTextHeadline", "gdpr-cc-plugin-options", "gdpr-cc-plugin-section");
    add_settings_field("gdpr_cc_text_button", __('Texto do botão', 'cookie-consent'), "gdprCookieTextAcceptButton", "gdpr-cc-plugin-options", "gdpr-cc-plugin-section");

    register_setting("gdpr-cc-plugin-section", "gdpr_cc_theme");
    register_setting("gdpr-cc-plugin-section", "gdpr_cc_text_headline");
    register_setting("gdpr-cc-plugin-section", "gdpr_cc_text_button");
}
add_action("admin_init", "gdprCookieFields");