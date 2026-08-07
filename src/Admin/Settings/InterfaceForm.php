<?php namespace Wc1c\Main\Admin\Settings;

defined('ABSPATH') || exit;

use Wc1c\Main\Exceptions\Exception;
use Wc1c\Main\Settings\InterfaceSettings;

/**
 *  InterfaceForm
 *
 * @package Wc1c\Main\Admin
 */
class InterfaceForm extends Form
{
	/**
	 * InterfaceForm constructor.
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->setId('settings-interface');
		$this->setSettings(new InterfaceSettings());

		add_filter('wc1c_' . $this->getId() . '_form_load_fields', [$this, 'init_fields_interface'], 10);

		$this->init();
	}
	/**
	 * Add for Interface
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function init_fields_interface($fields): array
    {
		$fields['admin_interface'] =
		[
			'title' => esc_html__('Changing the interface', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Allow changes to WordPress and WooCommerce dashboard interface?', 'wc1c-maincore'),
			'description' => sprintf
			(
				'%s <hr>%s',
                esc_html__('If enabled, new features will appear in the WordPress and WooCommerce interface according to the interface change settings.', 'wc1c-maincore'),
                esc_html__('If interface modification is enabled, it is possible to change settings for individual features, users, and roles. If disabled, features will be disabled globally for everyone and everything.', 'wc1c-maincore')
			),
			'default' => 'yes'
		];

		$fields['interface_title_woocommerce'] =
		[
			'title' => esc_html__('WooCommerce', 'wc1c-maincore'),
			'type' => 'title',
			'description' => esc_html__('Some interface settings for the WooCommerce.', 'wc1c-maincore'),
		];

		$fields['admin_interface_products_column'] =
		[
			'title' => esc_html__('Column in products list', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a column with information from 1C to the list of products.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		$fields['admin_interface_products_edit_metabox'] =
		[
			'title' => esc_html__('Metabox in edit products', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a Metabox with information from 1C in edit products.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		$fields['admin_interface_orders_column'] =
		[
			'title' => esc_html__('Column in orders list', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a column with information from 1C to the list of orders.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		$fields['admin_interface_orders_edit_metabox'] =
		[
			'title' => esc_html__('Metabox in edit orders', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a Metabox with information from 1C in edit orders.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		$fields['admin_interface_categories_column'] =
		[
			'title' => esc_html__('Column in categories list', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a column with information from 1C to the list of categories.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		$fields['interface_title_wordpress'] =
		[
			'title' => esc_html__('WordPress', 'wc1c-maincore'),
			'type' => 'title',
			'description' => esc_html__('Some interface settings for the WordPress.', 'wc1c-maincore'),
		];

		$fields['admin_interface_media_library_column'] =
		[
			'title' => esc_html__('Column in media library list', 'wc1c-maincore'),
			'type' => 'checkbox',
			'label' => esc_html__('Enable', 'wc1c-maincore'),
			'description' => esc_html__('Output of a column with information from 1C to the list of media files.', 'wc1c-maincore'),
			'default' => 'yes'
		];

		return $fields;
	}
}