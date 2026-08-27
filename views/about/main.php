<?php defined('ABSPATH') || exit; ?>
<?php
// Метаданные плагина
if ( ! function_exists( 'get_plugin_data' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$plugin_main_file = defined( 'WC1C_PLUGIN_FILE' ) ? WC1C_PLUGIN_FILE : '';
$plugin_data      = $plugin_main_file && file_exists( $plugin_main_file ) ? get_plugin_data( $plugin_main_file, false, false ) : array();
$plugin_name        = ! empty( $plugin_data['Name'] )        ? $plugin_data['Name']        : 'Интеграция WordPress ↔ 1С';
$plugin_description = ! empty( $plugin_data['Description'] ) ? $plugin_data['Description'] : 'Синхронизация сайта с 1С:Предприятие через CommerceML, REST, SOAP.';
$plugin_version     = ! empty( $plugin_data['Version'] )     ? $plugin_data['Version']     : '1.0.0';
$plugin_author      = ! empty( $plugin_data['Author'] )      ? $plugin_data['Author']      : 'WC1C team';
$plugin_author_uri  = ! empty( $plugin_data['AuthorURI'] )   ? $plugin_data['AuthorURI']   : '#';
$plugin_uri         = ! empty( $plugin_data['PluginURI'] )   ? $plugin_data['PluginURI']   : '#';
?>
<style>
    .wc1c-about * { box-sizing: border-box; }
    .wc1c-about { color: #1d2327; font-size: 14px; line-height: 1.6; }
    .wc1c-about h1, .wc1c-about h2, .wc1c-about h3 { color: #1d2327; font-weight: 600; }
    .wc1c-about a { color: #0073aa; text-decoration: none; }
    .wc1c-about a:hover { color: #005177; text-decoration: underline; }
    /* Hero */
    .wc1c-hero { background: #fff; border: 1px solid #c3c4c7; border-left: 4px solid #0073aa; border-radius: 4px; padding: 32px; margin-bottom: 24px; }
    .wc1c-hero-title { display: flex; align-items: flex-start; gap: 10px; flex-wrap: wrap; margin-bottom: 12px; }
    .wc1c-hero-title h1 { font-size: 30px; margin: 0; font-weight: 600; }
    .wc1c-hero-meta { display: flex; gap: 6px; align-items: center; margin-top: 3px; font-size: 13px; color: #50575e; }
    .wc1c-hero-meta .wc1c-badge { display: inline-block; padding: 3px 10px; background: #f0f6fc; color: #0073aa; border-radius: 3px; font-weight: 500; font-size: 12px; }
    .wc1c-hero-meta .wc1c-badge--free { background: #edfaef; color: #00a32a; }
    .wc1c-hero p { font-size: 16px; color: #50575e; margin: 0; max-width: 720px; }
    /* Key facts */
    .wc1c-facts { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
    @media (max-width: 780px) { .wc1c-facts { grid-template-columns: repeat(2, 1fr); } }
    .wc1c-fact { background: #fff; border: 1px solid #c3c4c7; border-radius: 4px; padding: 18px; }
    .wc1c-fact-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f0f6fc; color: #0073aa; border-radius: 4px; margin-bottom: 10px; }
    .wc1c-fact-title { font-size: 14px; font-weight: 600; margin: 0 0 4px; }
    .wc1c-fact-text { font-size: 13px; color: #50575e; margin: 0; line-height: 1.5; }
    /* Sections */
    .wc1c-section { background: #fff; border: 1px solid #c3c4c7; border-radius: 4px; padding: 28px; margin-bottom: 24px; }
    .wc1c-section h2 { font-size: 20px; margin: 0 0 6px; display: flex; align-items: center; gap: 8px; }
    .wc1c-section .wc1c-section-lead { color: #50575e; margin: 0 0 18px; font-size: 15px; }
    .wc1c-section p { margin: 0 0 12px; }
    /* Team contacts */
    .wc1c-contacts { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 18px; }
    @media (max-width: 640px) { .wc1c-contacts { grid-template-columns: 1fr; } }
    .wc1c-contact { padding: 14px; background: #f6f7f7; border-radius: 4px; }
    .wc1c-contact-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 4px; }
    .wc1c-contact-value { font-size: 14px; font-weight: 500; }
    /* Contribute */
    .wc1c-contribute-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin: 20px 0; }
    @media (max-width: 780px) { .wc1c-contribute-cards { grid-template-columns: 1fr; } }
    .wc1c-contribute-card { background: #f6f7f7; border: 1px solid #c3c4c7; border-radius: 4px; padding: 20px; display: flex; flex-direction: column; }
    .wc1c-contribute-card-icon { width: 40px; height: 40px; background: #0073aa; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
    .wc1c-contribute-card h3 { font-size: 16px; margin: 0 0 8px; }
    .wc1c-contribute-card p { font-size: 14px; color: #50575e; margin: 0 0 16px; line-height: 1.5; flex-grow: 1; }
    .wc1c-contribute-card .button { align-self: flex-start; }
    /* Hosting */
    .wc1c-hosting-note { background: #fcf9e8; border-left: 3px solid #dba617; padding: 12px 16px; border-radius: 0 4px 4px 0; margin-bottom: 20px; font-size: 14px; color: #614000; }
    .wc1c-hosting-note strong { color: #614000; }
    .wc1c-hostings { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 780px) { .wc1c-hostings { grid-template-columns: 1fr; } }
    .wc1c-hosting { padding: 25px; border-radius: 8px; position: relative; overflow: hidden; }
    .wc1c-hosting h3 { margin: 0 0 10px 0; font-size: 20px; color: #23282d; }
    .wc1c-hosting p { margin: 0 0 15px 0; color: #50575e; font-size: 14px; }
    .wc1c-hosting-promo { background: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
    .wc1c-hosting-promo code { background: #f0f0f1; padding: 5px 10px; border-radius: 3px; font-size: 16px; color: #0073aa; font-weight: 600; }
    .wc1c-hosting .button { width: 100%; text-align: center; display: block; }
    /* Service CTA */
    .wc1c-service { background: #f0f6fc; border: 1px solid #c3e0f5; border-radius: 4px; padding: 32px; text-align: center; }
    .wc1c-service h2 { justify-content: center; font-size: 22px; margin-bottom: 10px; }
    .wc1c-service p { max-width: 640px; margin: 0 auto 20px; color: #50575e; font-size: 15px; }
    .wc1c-service-features { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px auto; max-width: 720px; }
    @media (max-width: 640px) { .wc1c-service-features { grid-template-columns: repeat(2, 1fr); } }
    .wc1c-service-feature { background: #fff; border: 1px solid #c3e0f5; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 500; color: #1d2327; }
</style>
<div class="wc1c-about mt-2">
    <!-- HERO: суть плагина -->
    <div class="wc1c-hero">
        <div class="wc1c-hero-title">
            <h1><?php echo esc_html( $plugin_name ); ?></h1>
            <div class="wc1c-hero-meta">
                <span class="wc1c-badge">v<?php echo esc_html( $plugin_version ); ?></span>
                <span class="wc1c-badge wc1c-badge--free"><?php esc_html_e( 'Бесплатно', 'your-plugin-textdomain' ); ?></span>
            </div>
        </div>
        <p><?php echo esc_html( $plugin_description ); ?></p>
    </div>
    <!-- КЛЮЧЕВЫЕ ФАКТЫ: быстрый скан -->
    <div class="wc1c-facts">
        <div class="wc1c-fact">
            <div class="wc1c-fact-icon"><span class="dashicons dashicons-yes-alt"></span></div>
            <p class="wc1c-fact-title"><?php esc_html_e( 'Бесплатно навсегда', 'your-plugin-textdomain' ); ?></p>
            <p class="wc1c-fact-text"><?php esc_html_e( 'Без скрытых платежей и ограничений функционала.', 'your-plugin-textdomain' ); ?></p>
        </div>
        <div class="wc1c-fact">
            <div class="wc1c-fact-icon"><span class="dashicons dashicons-lock"></span></div>
            <p class="wc1c-fact-title"><?php esc_html_e( 'Лицензия GPLv3+', 'your-plugin-textdomain' ); ?></p>
            <p class="wc1c-fact-text"><?php esc_html_e( 'Право изучать, изменять и распространять исходный код.', 'your-plugin-textdomain' ); ?></p>
        </div>
        <div class="wc1c-fact">
            <div class="wc1c-fact-icon"><span class="dashicons dashicons-media-code"></span></div>
            <p class="wc1c-fact-title"><?php esc_html_e( 'Открытый код', 'your-plugin-textdomain' ); ?></p>
            <p class="wc1c-fact-text"><?php esc_html_e( 'Прозрачность и возможность стать контрибьютором.', 'your-plugin-textdomain' ); ?></p>
        </div>
        <div class="wc1c-fact">
            <div class="wc1c-fact-icon"><span class="dashicons dashicons-businessperson"></span></div>
            <p class="wc1c-fact-title"><?php esc_html_e( 'WC1C team', 'your-plugin-textdomain' ); ?></p>
            <p class="wc1c-fact-text"><?php esc_html_e( 'Основной разработчик и мейнтейнер проекта интеграции.', 'your-plugin-textdomain' ); ?></p>
        </div>
    </div>
    <!-- ОСНОВНОЙ РАЗРАБОТЧИК -->
    <div class="wc1c-section">
        <h2>
            <span class="dashicons dashicons-businessperson" style="color:#0073aa;"></span>
            <?php esc_html_e( 'Основной разработчик', 'your-plugin-textdomain' ); ?>
        </h2>
        <p class="wc1c-section-lead">
            <?php
            printf(
            /* translators: %s: author name with link */
                    esc_html__( 'Мейнтейнер проекта — %s. Команда вносит правки, развивает функционал и обеспечивает поддержку плагина для реальных платных пользователей — владельцев сервисного плагина.', 'your-plugin-textdomain' ),
                    '<a href="' . esc_url( $plugin_author_uri ) . '" target="_blank" rel="noopener"><strong>' . esc_html( $plugin_author ) . '</strong></a>'
            );
            ?>
        </p>
        <div style="background: #f0f6fc; border-left: 3px solid #0073aa; padding: 16px; border-radius: 0 4px 4px 0; margin-bottom: 18px;">
            <p style="margin: 0; font-size: 14px; color: #1d2327;">
                <strong><?php esc_html_e( 'Важно:', 'your-plugin-textdomain' ); ?></strong>
                <?php esc_html_e( 'Активная разработка, поддержка и сопровождение ведётся только для пользователей сервисного плагина. Для бесплатной версии поддержка осуществляется силами сообщества пользователей WordPress.', 'your-plugin-textdomain' ); ?>
            </p>
        </div>
        <p>
            <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-settings&tab=service' ) ); ?>">
                <?php esc_html_e( 'Узнать о сервисном плагине →', 'your-plugin-textdomain' ); ?>
            </a>
        </p>
    </div>
    <!-- КАК ПОМОЧЬ РАЗВИТИЮ ПРОЕКТА -->
    <div class="wc1c-section">
        <h2>
            <span class="dashicons dashicons-heart" style="color:#0073aa;"></span>
            <?php esc_html_e( 'Как помочь развитию проекта', 'your-plugin-textdomain' ); ?>
        </h2>
        <p class="wc1c-section-lead">
            <?php esc_html_e( 'Плагин развивается силами сообщества. Есть три способа поддержать проект:', 'your-plugin-textdomain' ); ?>
        </p>
        <div class="wc1c-contribute-cards">
            <div class="wc1c-contribute-card">
                <div class="wc1c-contribute-card-icon"><span class="dashicons dashicons-edit"></span></div>
                <h3><?php esc_html_e( 'Стать контрибьютором', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'Найдите баг, предложите улучшение, отправьте pull request или помогите с документацией. Каждая правка делает плагин лучше для всех пользователей.', 'your-plugin-textdomain' ); ?></p>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-contribute' ) ); ?>">
                    <?php esc_html_e( 'Как участвовать', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
            <div class="wc1c-contribute-card">
                <div class="wc1c-contribute-card-icon"><span class="dashicons dashicons-cloud"></span></div>
                <h3><?php esc_html_e( 'Использовать рекомендуемые хостинги', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'При переходе по ссылкам мы получаем партнёрское вознаграждение — оно идёт на разработку плагина. Для вас цена не меняется, а часто даже снижается по промокоду.', 'your-plugin-textdomain' ); ?></p>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-hostings' ) ); ?>">
                    <?php esc_html_e( 'Выбрать хостинг', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
            <div class="wc1c-contribute-card">
                <div class="wc1c-contribute-card-icon"><span class="dashicons dashicons-awards"></span></div>
                <h3><?php esc_html_e( 'Использовать сервисы команды', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'Сервисный плагин и другие платные сервисы команды — это прямой способ спонсировать развитие проекта и получать расширенные возможности.', 'your-plugin-textdomain' ); ?></p>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-settings&tab=service' ) ); ?>">
                    <?php esc_html_e( 'Сервисы команды', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
        </div>
    </div>
    <!-- ХОСТИНГИ -->
    <div class="wc1c-section">
        <h2>
            <span class="dashicons dashicons-cloud" style="color:#0073aa;"></span>
            <?php esc_html_e( 'Рекомендуемые хостинги', 'your-plugin-textdomain' ); ?>
        </h2>
        <div class="wc1c-hosting-note">
            <strong><?php esc_html_e( 'Поддержите проект без вложений.', 'your-plugin-textdomain' ); ?></strong>
            <?php esc_html_e( 'Используя эти хостинги по промокодам, вы помогаете финансировать разработку плагина.', 'your-plugin-textdomain' ); ?>
        </div>
        <div class="wc1c-hostings">
            <div class="wc1c-hosting" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                <div style="position: absolute; top: -10px; right: -10px; background: #0073aa; color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    <?php esc_html_e( 'Рекомендуем', 'your-plugin-textdomain' ); ?>
                </div>
                <h3><?php esc_html_e( 'Хостинг №1', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'Оптимизирован под WordPress и 1С-обмены.', 'your-plugin-textdomain' ); ?></p>
                <div class="wc1c-hosting-promo">
                    <strong style="color: #23282d;"><?php esc_html_e( 'Промокод:', 'your-plugin-textdomain' ); ?></strong><br>
                    <code>WC4B10</code>
                </div>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-hosting-details&hosting=1' ) ); ?>">
                    <?php esc_html_e( 'Подробнее', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
            <div class="wc1c-hosting" style="background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);">
                <h3><?php esc_html_e( 'Хостинг №2', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'VPS с гибкой настройкой под высокие нагрузки.', 'your-plugin-textdomain' ); ?></p>
                <div class="wc1c-hosting-promo">
                    <strong style="color: #23282d;"><?php esc_html_e( 'Промокод:', 'your-plugin-textdomain' ); ?></strong><br>
                    <code>WC4B-VPS</code>
                </div>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-hosting-details&hosting=2' ) ); ?>">
                    <?php esc_html_e( 'Подробнее', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
            <div class="wc1c-hosting" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <h3><?php esc_html_e( 'Хостинг №3', 'your-plugin-textdomain' ); ?></h3>
                <p><?php esc_html_e( 'Хостинг с поддержкой РФ/РБ.', 'your-plugin-textdomain' ); ?></p>
                <div class="wc1c-hosting-promo">
                    <strong style="color: #23282d;"><?php esc_html_e( 'Промокод:', 'your-plugin-textdomain' ); ?></strong><br>
                    <code>WC4B-BY</code>
                </div>
                <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-hosting-details&hosting=3' ) ); ?>">
                    <?php esc_html_e( 'Подробнее', 'your-plugin-textdomain' ); ?>
                </a>
            </div>
        </div>
    </div>
    <!-- СЕРВИСНЫЙ ПЛАГИН: финальный CTA -->
    <div class="wc1c-service">
        <h2>
            <span class="dashicons dashicons-awards" style="color:#0073aa;"></span>
            <?php esc_html_e( 'Сервисный плагин', 'your-plugin-textdomain' ); ?>
        </h2>
        <p>
            <?php esc_html_e( 'Расширенные алгоритмы обмена, мониторинг, авто-диагностика и приоритетная поддержка. Пользователи сервисного плагина напрямую спонсируют развитие проекта.', 'your-plugin-textdomain' ); ?>
        </p>
        <div class="wc1c-service-features">
            <div class="wc1c-service-feature"><?php esc_html_e( 'Расширенные обмены', 'your-plugin-textdomain' ); ?></div>
            <div class="wc1c-service-feature"><?php esc_html_e( 'Мониторинг 24/7', 'your-plugin-textdomain' ); ?></div>
            <div class="wc1c-service-feature"><?php esc_html_e( 'Авто-диагностика', 'your-plugin-textdomain' ); ?></div>
            <div class="wc1c-service-feature"><?php esc_html_e( 'Приоритетная поддержка', 'your-plugin-textdomain' ); ?></div>
        </div>
        <a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'admin.php?page=wc1c-settings&tab=service' ) ); ?>">
            <?php esc_html_e( 'Узнать больше →', 'your-plugin-textdomain' ); ?>
        </a>
    </div>
</div>