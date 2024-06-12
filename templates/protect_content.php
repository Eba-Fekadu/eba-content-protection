<?php
if(!defined('WPINC')){
    exit("You can't access this file directly");
}
?>

<div class="category-protection-settings">
    <div class="category-protection-check">
        
    <h2>Protected Categories</h2>
    <div class="checkboxes">
        <?php foreach ($checkbox_fields as $field) : ?>
            <p>
                <input type="checkbox" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="1" <?php checked($field['value'], 1); ?>>
                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['label']); ?></label>
            </p>
        <?php endforeach; ?>
    </div>
    </div>
    <div class="category-protection-password">

    <h2>Category Passwords</h2>
    <div class="passwords">
        <?php foreach ($password_fields as $field) : ?>
            <div class="form-row">
                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['label']); ?></label>
                <input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($field['value']); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    </div>
</div>