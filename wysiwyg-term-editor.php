<?php
/*
Plugin Name: Wysiwyg Term Editor
Plugin URI: https://maximehardy.me/
Description: Ce plugin ajoute un éditeur WYSIWYG pour les termes de taxonomie.
Version: 1.2
Author: Maxime HARDY
Author URI: https://maximehardy.me/
*/

if (!defined('ABSPATH')) exit; // Sécurité : Empêche l'accès direct

class Wysiwyg_Term_Editor 
{
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this,'clb_admin_category_style'], 11);
        add_action('category_edit_form_fields', [$this, 'clbs_add_category_description_editor']);
        add_action('admin_head', [$this, 'clbs_remove_default_category_description']);
        remove_filter( 'pre_term_description', 'wp_filter_kses' );
        remove_filter( 'term_description', 'wp_kses_data' );
    }

    function clbs_add_category_description_editor($tag) 
    {
        $description = htmlspecialchars_decode($tag->description);
        ?>
            <table class="form-table">
                <tr class="form-field">
                    <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
                    <td>
                    <?php
                        $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
                        wp_editor(wp_kses_post($description , ENT_QUOTES, 'UTF-8'), 'cat_description', $settings);
                    ?>
                    <br />
                    <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
                    </td>
                </tr>
            </table>
        <?php
    }

    function clbs_remove_default_category_description() {
        global $current_screen;
        if ( $current_screen->id == 'edit-category' )
        {
            ?>
                <script type="text/javascript">
                jQuery(function($) {
                    $('textarea#description').closest('tr.form-field').remove();
                    });
                </script>
            <?php
        }
    }

    function clb_admin_category_style() {
        wp_enqueue_style('clb-admin-category-style', plugins_url('assets/admin.css', __FILE__));
    }
}

// Initialisation de la classe
new Wysiwyg_Term_Editor();