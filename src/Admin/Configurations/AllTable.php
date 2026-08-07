<?php namespace Wc1c\Main\Admin\Configurations;

// phpcs:disable WordPress.Security.NonceVerification.Recommended

defined('ABSPATH') || exit;

use Wc1c\Main\Abstracts\TableAbstract;
use Wc1c\Main\Configuration;
use Wc1c\Main\Data\Storage;
use Wc1c\Main\Data\Storages\ConfigurationsStorage;
use Wc1c\Main\Traits\ConfigurationsUtilityTrait;
use Wc1c\Main\Traits\DatetimeUtilityTrait;
use Wc1c\Main\Traits\UtilityTrait;

/**
 * AllTable
 *
 * @package Wc1c\Main\Admin\Configurations
 */
class AllTable extends TableAbstract
{
    use ConfigurationsUtilityTrait;
    use DatetimeUtilityTrait;
    use UtilityTrait;

    /**
     * @var ConfigurationsStorage Configurations storage
     */
    public $storage_configurations;

    /**
     * AllTable constructor.
     */
    public function __construct()
    {
        $params =
            [
                'singular' => 'configuration',
                'plural' => 'configurations',
                'ajax' => false
            ];

        try
        {
            $this->storage_configurations = Storage::load('configuration');
        }
        catch(\Throwable $e)
        {}

        parent::__construct($params);
    }

    /**
     * No items found text
     */
    public function noItems()
    {
        $args['url_create'] = $this->utilityAdminConfigurationsGetUrl('create');

        wc1c()->views()->getView('configurations/empty.php', $args);
    }

    /**
     * Get a list of CSS classes for the WP_List_Table table tag
     *
     * @return array - list of CSS classes for the table tag
     */
    protected function getTableClasses(): array
    {
        return
            [
                'widefat',
                'striped',
                $this->_args['plural']
            ];
    }

    /**
     * Default print rows
     *
     * @param object $item
     * @param string $column_name
     *
     * @return string
     */
    public function columnDefault($item, string $column_name): string
    {
        switch ($column_name)
        {
            case 'configuration_id':
                return esc_html($item['configuration_id']);
            case 'schema':
                return esc_html($item['schema']);
            case 'date_create':
            case 'date_activity':
            case 'date_modify':
                return $this->prettyColumnsDate($item, $column_name);
            default:
                // Заменяем print_r на понятное сообщение
                return esc_html__('No data available.', 'wc1c-maincore');
        }
    }

    /**
     * @param $item
     * @param $column_name
     *
     * @return string
     */
    private function prettyColumnsDate($item, $column_name): string
    {
        $date = $item[$column_name];
        $timestamp = $this->utilityStringToTimestamp($date) + $this->utilityTimezoneOffset();

        if(!empty($date))
        {
            return sprintf
            (
                '%s <br/><span class="time">%s: %s</span><br>%s',
                date_i18n('d/m/Y', $timestamp),
                __('Time', 'wc1c-maincore'),
                date_i18n('H:i:s', $timestamp),
                /* translators: human-readable time difference */
                sprintf(_x('(%s ago)', '%s = human-readable time difference', 'wc1c-maincore'), human_time_diff($timestamp, current_time('timestamp')))
            );
        }

        return __('No activity', 'wc1c-maincore');
    }

    /**
     * Configuration status
     *
     * @param $item
     *
     * @return string
     */
    public function columnStatus($item): string
    {
        $status = $this->utilityConfigurationsGetStatusesLabel($item['status']);

        $status_class = '';
        $status_description = '';

        if($item['status'] === 'draft')
        {
            $status_class = 'draft';
            $status_description = __('An initial configuration setup is required.', 'wc1c-maincore');
        }
        if($item['status'] === 'active')
        {
            $status_class = 'active';
            $status_description = __('All configuration algorithms are active.', 'wc1c-maincore');
        }
        if($item['status'] === 'inactive')
        {
            $status_class = 'inactive';
            $status_description = __('All configuration algorithms are disabled. Configuration is switched off in the settings.', 'wc1c-maincore');
        }
        if($item['status'] === 'processing')
        {
            $status_class = 'processing';
            $status_description = __('Data is being exchanged. Changing configuration settings is not recommended.', 'wc1c-maincore');
        }
        if($item['status'] === 'error')
        {
            $status_class = 'error';
            $status_description = __('An error has occurred. Should review the event logs for the configuration, they contain detailed information about the error.', 'wc1c-maincore');
        }
        if($item['status'] === 'deleted')
        {
            $status_class = 'deleted';
            $status_description = __('Awaiting final configuration removal. All algorithms are disabled.', 'wc1c-maincore');
        }

        return sprintf
        (
            '<span class="%s" data-bs-toggle="popover" data-bs-custom-class="configurations-status-popover %s" data-bs-title="%s" data-bs-trigger="hover focus click" data-bs-content="%s">%s</span>',
            esc_attr($status_class),
            esc_attr($status_class),
            esc_attr__('Status description', 'wc1c-maincore'),
            esc_attr($status_description),
            esc_html($status)
        );
    }

    /**
     * Configuration name
     *
     * @param $item
     *
     * @return string
     */
    public function columnName($item): string
    {
        try
        {
            $configuration = new Configuration($item['configuration_id']);
        }
        catch(\Throwable $e)
        {
            return $e->getMessage();
        }

        $actions = [
            'update' => '<a href="' . esc_url($this->utilityAdminConfigurationsGetUrl('update', $item['configuration_id'])) . '">' . esc_html__('Open configuration', 'wc1c-maincore') . '</a>',
            'delete' => '<a href="' . esc_url($this->utilityAdminConfigurationsGetUrl('delete', $item['configuration_id'])) . '">' . esc_html__('Mark as deleted', 'wc1c-maincore') . '</a>',
        ];

        if('deleted' === $item['status'] || ('draft' === $item['status'] && 'yes' === wc1c()->settings()->get('configurations_draft_delete', 'yes')))
        {
            $actions['delete'] = '<a href="' . $this->utilityAdminConfigurationsGetUrl('delete', $item['configuration_id']) . '">' . __('Remove forever', 'wc1c-maincore') . '</a>';
        }

        if($configuration->isEnabled() && 'deleted' !== $item['status'])
        {
            unset($actions['delete']);
        }

        $actions = apply_filters('wc1c_admin_configurations_all_row_actions', $actions, $item);

        $user = get_userdata($item['user_id']);
        if($user instanceof \WP_User && $user->exists())
        {
            $metas['user'] = esc_html__('User:', 'wc1c-maincore') . ' <span class="row-metas-line-content">' . esc_html($user->get('nickname')) . ' (' . esc_html($item['user_id']) . ')</span>';
        }
        else
        {
            $metas['user'] =  esc_html__('User:', 'wc1c-maincore') . ' <span class="row-metas-line-content">' . esc_html__('user is not exists.', 'wc1c-maincore') . '</span>';
        }

        try
        {
            $schema = wc1c()->schemas()->get($item['schema']);

            $metas['schema'] = esc_html__('Schema:', 'wc1c-maincore') . ' <span class="row-metas-line-content">' . esc_html($item['schema']) . ' (' . esc_html($schema->getName()) . ')</span>';

            if($item['schema'] === 'productscml' || $item['schema'] === 'pqcml')
            {
                $full_time = $configuration->getMeta('_catalog_full_time', true);

                if(!empty($full_time))
                {
                    $timestamp = $full_time + $this->utilityTimezoneOffset();

                    $metas['productscml-catalog-full'] = sprintf
                    (
                        '%1$s <span class="row-metas-line-content">%2$s (<span class="time">%3$s %4$s</span> %5$s)</span>',
                        esc_html__('Full exchange:', 'wc1c-maincore'),
                        /* translators: human-readable time difference */
                        sprintf(_x('%s ago', '%s = human-readable time difference', 'wc1c-maincore'), human_time_diff($timestamp, current_time('timestamp'))),
                        date_i18n('d/m/Y', $timestamp),
                        esc_html__('at', 'wc1c-maincore'),
                        date_i18n('H:i:s', $timestamp)
                    );
                }
                else
                {
                    $metas['productscml-catalog-full'] = sprintf
                    (
                        '%1$s <span class="row-metas-line-content">%2$s</span>',
                        esc_html__('Full exchange:', 'wc1c-maincore'),
                        esc_html__('not produced', 'wc1c-maincore')
                    );
                }
            }
        }
        catch(\Throwable $e)
        {
            $metas['schema'] = esc_html__('Schema:', 'wc1c-maincore') . ' <span class="row-metas-line-content">' . esc_html($item['schema']) . ' (' . esc_html__('not found, please install the schema', 'wc1c-maincore') . ')</span>';
        }

        if(has_filter('wc1c_admin_configurations_all_row_metas'))
        {
            $metas = apply_filters('wc1c_admin_configurations_all_row_metas', $metas, $item);
        }

        return sprintf(
            '<span class="configuration-name">%1$s</span><div class="configuration-metas">%2$s</div><div class="configuration-actions">%3$s</div>',
            esc_html($item['name']),
            $this->rowMetas($metas),
            $this->rowActions($actions, true)
        );
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function rowMetas($data): string
    {
        $metas_count = count($data);
        if(!$metas_count) return '';

        $out = '<div class="row-metas">';
        foreach($data as $meta => $meta_text)
        {
            $out .= '<div class="row-metas-line ' . esc_attr($meta) . '">' . wp_kses_post($meta_text) . '</div>';
        }
        $out .= '</div>';
        return $out;
    }

    /**
     * All columns
     *
     * @return array
     */
    public function getColumns(): array
    {
        $columns = [];

        $columns['configuration_id'] = __('ID', 'wc1c-maincore');
        $columns['name'] = __('Base information', 'wc1c-maincore');
        $columns['status'] = __('Status', 'wc1c-maincore');
        $columns['date_create'] = __('Create date', 'wc1c-maincore');
        $columns['date_activity'] = __('Last activity', 'wc1c-maincore');

        return $columns;
    }

    /**
     * Sortable columns
     *
     * @return array
     */
    public function getSortableColumns(): array
    {
        $sortable_columns['configuration_id'] = ['configuration_id', false];
        $sortable_columns['status'] = ['status', false];
        $sortable_columns['date_create'] = ['date_create', true];
        $sortable_columns['date_activity'] = ['date_activity', true];

        return $sortable_columns;
    }

    /**
     * Gets the name of the primary column.
     *
     * @return string The name of the primary column
     */
    protected function getDefaultPrimaryColumnName(): string
    {
        return 'configuration_id';
    }

    /**
     * Creates the different status filter links at the top of the table.
     *
     * @return array
     */
    protected function getViews(): array
    {
        $status_links = [];
        // Nonce не требуется, это только чтение параметра фильтрации.
        $current = !empty($_REQUEST['status']) ? sanitize_key(wp_unslash($_REQUEST['status'])) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        // All link
        $class = $current === 'all' ? ' class=current' :'';
        $all_url = esc_url(remove_query_arg('status'));

        $status_links['all'] = sprintf
        (
            '<a href="%1$s" %2$s>%3$s <span class="count">(%4$d)</span></a>',
            esc_url($all_url),
            esc_html($class),
            esc_html__('All', 'wc1c-maincore'),
            absint($this->storage_configurations->count())
        );

        $statuses = $this->utilityConfigurationsGetStatuses();

        foreach($statuses as $status_key)
        {
            $count = $this->storage_configurations->countBy
            (
                [
                    'status' => $status_key
                ]
            );

            if($count === 0)
            {
                continue;
            }

            $class = $current === $status_key ? ' class=current' : '';
            $sold_url = esc_url(add_query_arg('status', $status_key));

            $status_links[$status_key] = sprintf
            (
                '<a href="%s" %s>%s <span class="count">(%d)</span></a>',
                esc_url($sold_url),
                esc_html($class),
                esc_html($this->utilityConfigurationsGetStatusesFolder($status_key)),
                absint($count)
            );
        }

        return $status_links;
    }

    /**
     * Build items
     */
    public function prepareItems()
    {
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = wc1c()->settings()->get('configurations_show_per_page', 10);

        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->getColumns();
        $hidden = [];
        $sortable = $this->getSortableColumns();

        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = [$columns, $hidden, $sortable];

        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->getPagenum();

        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example
         * package slightly different than one you might build on your own. In
         * this example, we'll be using array manipulation to sort and paginate
         * our data. In a real-world implementation, you will probably want to
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $offset = 0;

        if(1 < $current_page)
        {
            $offset = $per_page * ($current_page - 1);
        }

        $allowed_orderby = ['configuration_id', 'status', 'date_create', 'date_activity', 'name'];
        // Nonce не требуется, это только параметры сортировки.
        $orderby = (!empty($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], $allowed_orderby, true))
            ? sanitize_key(wp_unslash($_REQUEST['orderby'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            : 'configuration_id';

        $allowed_order = ['asc', 'desc'];
        $order = (!empty($_REQUEST['order']) && in_array(strtolower($_REQUEST['order']), $allowed_order, true))
            ? strtolower(sanitize_key(wp_unslash($_REQUEST['order']))) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            : 'desc';

        $storage_args['orderby'] = $orderby;
        $storage_args['order'] = $order;

        // Nonce не требуется, это только параметры фильтрации.
        $storage_args = [];

        if(isset($_GET['status']) && in_array(sanitize_key(wp_unslash($_GET['status'])), $this->utilityConfigurationsGetStatuses(), true))
        {
            $storage_args['status'] = sanitize_key(wp_unslash($_GET['status'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }

        if(!empty($_REQUEST['s'])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        {
            $search_text = sanitize_text_field(wp_unslash($_REQUEST['s']));
            $storage_args['name'] =
            [
                'value' => $search_text,
                'compare_key' => 'LIKE'
            ];
        }

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        if(empty($storage_args))
        {
            $total_items = $this->storage_configurations->count();
        }
        else
        {
            $total_items = $this->storage_configurations->countBy($storage_args);
        }

        $storage_args['offset'] = $offset;
        $storage_args['limit'] = $per_page;
        $storage_args['orderby'] = $orderby;
        $storage_args['order'] = $order;

        $this->items = $this->storage_configurations->getData($storage_args, ARRAY_A);

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->setPaginationArgs
        (
            [
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ]
        );
    }

    /**
     * Extra controls to be displayed between bulk actions and pagination
     *
     * @param string $which
     */
    protected function extraTablenav(string $which)
    {
        if('top' === $which)
        {
            $this->views();

            $this->searchBox(esc_html__('Search', 'wc1c-maincore'), 'code');
        }
    }

    /**
     * Search box
     *
     * @param string $text Button text
     * @param string $input_id Input ID
     */
    public function searchBox(string $text, string $input_id)
    {
        // Nonce не требуется, это только чтение.
        if(empty($_REQUEST['s']) && !$this->hasItems()) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        {
            return;
        }

        $input_id = $input_id . '-search-input';
        $searchQuery = isset($_REQUEST['s']) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';

        echo '<p class="search-box">';
        echo '<label class="screen-reader-text" for="' . esc_attr($input_id) . '">' . esc_html($text) . ':</label>';
        echo '<input type="search" id="' . esc_attr($input_id) . '" name="s" value="' . esc_attr($searchQuery) . '" />';
        submit_button($text, '', '', false, array('id' => 'search-submit'));
        echo '</p>';
    }
}