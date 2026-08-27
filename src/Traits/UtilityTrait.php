<?php namespace Wc1c\Main\Traits;

defined('ABSPATH') || exit;

/**
 * UtilityTrait
 *
 * @package Wc1c\Main\Traits
 */
trait UtilityTrait
{
	/**
	 * Convert kb, mb, gb to bytes
	 *
	 * @param $size
	 *
	 * @return float|int
	 */
	public function utilityConvertFileSize($size)
	{
		if(empty($size))
		{
			return 0;
		}

		$type = $size[strlen($size) - 1];

		if(!is_numeric($type))
		{
			$size = (int) $size;

			switch($type)
			{
				case 'K':
					$size *= 1024;
					break;
				case 'M':
					$size *= 1024 * 1024;
					break;
				case 'G':
					$size *= 1024 * 1024 * 1024;
					break;
				default:
					return $size;
			}
		}

		return (int)$size;
	}

	/**
	 * Is WC1C admin tools request?
	 *
	 * @param string $tool_id
	 *
	 * @return bool
	 */
	public function utilityIsWc1cAdminToolsRequest($tool_id = '')
	{
		if(true !== $this->utilityIsWc1cAdminSectionRequest('tools'))
		{
			return false;
		}

		if('' === $tool_id)
		{
			return true;
		}

		$get_tool_id = isset($_GET['tool_id']) ? sanitize_text_field(wp_unslash($_GET['tool_id'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if($get_tool_id !== $tool_id)
		{
			return false;
		}

		return true;
	}

	/**
	 * Is WC1C admin request?
	 *
	 * @return bool
	 */
	public function utilityIsWc1cAdmin()
	{
		if(false !== is_admin() && 'wc1c' === (isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '')) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		{
			return true;
		}

		return false;
	}

	/**
	 * Is WC1C admin section request?
	 *
	 * @param string $section
	 *
	 * @return bool
	 */
	public function utilityIsWc1cAdminSectionRequest($section = '')
	{
		if((isset($_GET['section']) ? sanitize_key(wp_unslash($_GET['section'])) : '') !== $section) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		{
			return false;
		}

		if($this->utilityIsWc1cAdmin())
		{
			return true;
		}

		return false;
	}

	/**
	 * @param string $tool_id
	 *
	 * @return string
	 */
	public function utilityAdminToolsGetUrl($tool_id = '')
	{
		$path = 'admin.php?page=wc1c&section=tools';

		if('' === $tool_id)
		{
			return admin_url($path);
		}

		$path = 'admin.php?page=wc1c&section=tools&tool_id=' . $tool_id;

		return admin_url($path);
	}

	/**
	 * @param string $action
	 * @param string $configuration_id
	 *
	 * @return string
	 */
	public function utilityAdminConfigurationsGetUrl($action = 'all', $configuration_id = '')
	{
		$path = 'admin.php?page=wc1c&section=configurations';

		if('all' !== $action)
		{
			$path .= '&do_action=' . $action;
		}

		if('' === $configuration_id)
		{
			return admin_url($path);
		}

		$path .= '&configuration_id=' . $configuration_id;

		// Add nonce for actions that modify data (delete, update, etc.)
		if(in_array($action, ['delete', 'update'], true))
		{
			$nonce_action = 'wc1c_' . $action . '_configuration_' . $configuration_id;
			$path .= '&_wc1c_nonce=' . wp_create_nonce($nonce_action);
		}

		return admin_url($path);
	}

	/**
	 * @param $data
	 * @param bool $die
	 *
	 * @return void
	 */
	public function dump($data, $die = false)
	{
		if (defined('WP_DEBUG') && WP_DEBUG)
		{
			echo '<pre>';
			var_dump($data); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_dump
			echo '</pre>';
		}

		if ($die)
		{
			die;
		}
	}
}