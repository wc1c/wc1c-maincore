<?php namespace Wc1c\Main\Schemas\Abstracts\Cml;

defined('ABSPATH') || exit;

/**
 * ReceiverAbstract
 *
 * @package Wc1c\Main\Abstracts\Cml
 */
abstract class ReceiverAbstract
{
    /**
     * @var string Current mode
     */
    public $mode = '';

    /**
     * @var string Current type
     */
    public $type = '';

    /**
     * Detects mode and type from various GET parameters.
     *
     * @return array Associative array with keys 'mode' and 'type'.
     */
    public function detectModeAndType(): array
    {
        if(!empty($this->getType()) && !empty($this->getMode()))
        {
            return
            [
                'mode' => $this->getMode(),
                'type' => $this->getType()
            ];
        }

        $mode = '';
        $type = '';

        $mode_get = filter_input(INPUT_GET, 'mode', FILTER_UNSAFE_RAW);
        $type_get = filter_input(INPUT_GET, 'type', FILTER_UNSAFE_RAW);

        if ($mode_get !== null && $mode_get !== false)
        {
            $mode = sanitize_key($mode_get);
        }
        if ($type_get !== null && $type_get !== false)
        {
            $type = sanitize_key($type_get);
        }

        $get_param = filter_input(INPUT_GET, 'get_param', FILTER_UNSAFE_RAW);
        $get_param_type = filter_input(INPUT_GET, 'get_param?type', FILTER_UNSAFE_RAW);

        if (!empty($get_param) || !empty($get_param_type))
        {
            $output = [];

            if (!empty($get_param))
            {
                $param_str = sanitize_text_field($get_param);
                parse_str($param_str, $output);
            }

            if (isset($output['mode']) && $output['mode'] !== '')
            {
                $mode = sanitize_key($output['mode']);
            }

            if (isset($output['type']) && $output['type'] !== '')
            {
                $type = sanitize_key($output['type']);
            }

            if (empty($type) && !empty($get_param_type))
            {
                $type = sanitize_key($get_param_type);
            }
        }

        $this->setMode($mode);
        $this->setType($type);

        return
        [
            'mode' => $mode,
            'type' => $type
        ];
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
}