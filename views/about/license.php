<?php defined('ABSPATH') || exit; ?>

<?php
$license_file = plugin_dir_path( WC1C_PLUGIN_FILE ) . 'license.txt';
$license_text = file_exists( $license_file )
        ? file_get_contents( $license_file )
        : __( 'Файл лицензии не найден.', 'your-plugin-textdomain' );
?>

<div style="background: white; padding: 30px; border-radius: 8px; margin-top:10px;box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <h2 style="margin: 0 0 15px 0; font-size: 24px; color: #23282d;">
        <span class="dashicons dashicons-lock" style="font-size: 24px; vertical-align: middle; margin-right: 8px; color: #0073aa;"></span>
        <?php esc_html_e( 'Лицензионное соглашение', 'your-plugin-textdomain' ); ?>
    </h2>
    <p style="font-size: 16px; line-height: 1.6; color: #50575e; margin: 0;">
        <?php esc_html_e(
                'Полный текст лицензии, распространяемой на плагин. Файл находится в корне плагина (license.txt).',
                'your-plugin-textdomain'
        ); ?>
    </p>
    <pre style="white-space: pre-wrap; word-wrap: break-word; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; max-height: 600px; overflow: auto; background: #f6f7f7; padding: 20px; border: 1px solid #dcdcde; border-radius: 5px; color: #23282d;"><?php
        echo esc_html( $license_text );
        ?></pre>
</div>
