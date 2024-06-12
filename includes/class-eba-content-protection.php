<?php

//check if file is accessed directly
if(!defined('WPINC')){
    exit("You can't access this file directly");
}

/**
 * Eba Content Protection
 */

class Eba_Content_Protection
{

  // Initialize the plugin
    public function init() {
        add_action('admin_menu', [$this, 'create_settings_page']);
        add_action('admin_init', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_fields']);
        add_filter('the_content', [$this, 'protect_content']);
    }

    // Create the settings page
    public function create_settings_page() {
        add_options_page(
            'Content Protection',
            'Content Protection',
            'manage_options',
            'content-protection',
            [$this, 'settings_page_content'],
            'dashicons-lock'
        );
    }

    // Render the settings page content
    public function settings_page_content() {
        $checkbox_fields = [];
        $password_fields = [];

        $categories = get_categories(['hide_empty' => false]);
        foreach ($categories as $category) {
            $checkbox_fields[] = [
                'type' => 'checkbox',
                'id' => 'protect_category_' . $category->term_id,
                'value' => get_option('protect_category_' . $category->term_id),
                'label' => $category->name,
            ];
            $password_fields[] = [
                'type' => 'password',
                'id' => 'password_category_' . $category->term_id,
                'value' => get_option('password_category_' . $category->term_id),
                'label' => $category->name,
            ];
        }

        // Render the settings page content
        ?>
        <div class="wrap">
            <h1>Content Protection Settings</h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('content_protection');
                    include EBA_CONTENT_PROTECTION_DIR . '/templates/protect_content.php';
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // Setup settings sections
    public function setup_sections() {
        add_settings_section('content_protection_section', 'Protected Categories and Passwords', [], 'content-protection');
    }

    // Setup settings fields
    public function setup_fields() {
        $categories = get_categories(['hide_empty' => false]);
        foreach ($categories as $category) {
            register_setting('content_protection', 'protect_category_' . $category->term_id);
            register_setting('content_protection', 'password_category_' . $category->term_id);
        }
    }

     // Protect content in selected categories with passwords
    public function protect_content($content) {
        if (is_single() && in_the_loop() && is_main_query()) {
            $categories = get_the_category();
            foreach ($categories as $category) {
                $is_protected = get_option('protect_category_' . $category->term_id);
                $password = get_option('password_category_' . $category->term_id);

                if ($is_protected && $password) {
                    if (isset($_POST['category_password']) && $_POST['category_password'] === $password) {
                        setcookie('category_' . $category->term_id . '_password', $password, time() + 3600, '/');
                    } elseif (!isset($_COOKIE['category_' . $category->term_id . '_password']) || $_COOKIE['category_' . $category->term_id . '_password'] !== $password) {
                        // Show the first paragraph and the password form
                        return wpautop($this->get_first_paragraph($content)) . $this->get_password_form();
                    }
                }
            }
        }
        return $content;
    }

    // Get the password form
    public function get_password_form() {
        ob_start();
        include EBA_CONTENT_PROTECTION_DIR . '/templates/password-form.php';
        return ob_get_clean();
    }

    // Get the first paragraph of content
    public function get_first_paragraph($content) {
        $paragraphs = explode("\n", wpautop($content));
        return strip_tags($paragraphs[0], '<p><a><strong><em><br><span><ul><li><ol><blockquote>');
    }
}

$init = new Eba_Content_Protection();
$init ->init();