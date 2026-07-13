<?php declare(strict_types=1);

namespace Wc1c\Main;

defined('ABSPATH') || exit;

/**
 * Transliterator
 *
 * Universal dictionary covering all Cyrillic languages used in 1C countries:
 * Russia, Ukraine, Belarus, Kazakhstan, Kyrgyzstan, Tajikistan, Bulgaria,
 * Serbia, North Macedonia, Mongolia, Tatarstan, Bashkortostan, Chuvashia,
 * Yakutia, and others.
 *
 * @package Wc1c\Main
 *
 * @since  0.24.0
 */
final class Transliterator
{
    /**
     * Default maximum lengths by context.
     *
     * @var array<string, int>
     */
    protected const DEFAULT_LIMITS =
    [
        'slug' => 190, // Posts/products: post_name = varchar(200), buffer for suffixes -2...-10
        'term_slug' => 150, // Flat taxonomies (tags, brands)
        'term_slug_hier' => 50, // Hierarchical taxonomies (categories) — short URL segments
        'attribute_slug' => 29, // WooCommerce attributes (pa_*)
        'filename' => 0, // File names — no limit
        'sku' => 0, // SKUs — no limit
        'raw' => 0, // No sanitization or limit
    ];

    /**
     * Universal transliteration dictionary.
     *
     * Contains ALL unique Cyrillic characters without duplication.
     * Common characters (а, б, в...) use Russian standard as baseline.
     * Language-specific characters (ґ, ў, ә, ҳ, ѓ...) have their own mappings.
     *
     * @var array<string, string>
     */
    protected array $dictionary =
    [
        // ═══════════════════════════════════════════════════════════════
        // BASE CHARACTERS (same for all Cyrillic languages)
        // Transliteration follows Russian standard (most common in 1C)
        // ═══════════════════════════════════════════════════════════════
        'а' => 'a',   'б' => 'b',   'в' => 'v',   'г' => 'g',   'д' => 'd',
        'е' => 'e',   'ё' => 'yo',  'ж' => 'zh',  'з' => 'z',   'и' => 'i',
        'й' => 'y',   'к' => 'k',   'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',   'с' => 's',   'т' => 't',
        'у' => 'u',   'ф' => 'f',   'х' => 'kh',  'ц' => 'ts',  'ч' => 'ch',
        'ш' => 'sh',  'щ' => 'shch','ъ' => '',    'ы' => 'y',   'ь' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',   'Г' => 'G',   'Д' => 'D',
        'Е' => 'E',   'Ё' => 'Yo',  'Ж' => 'Zh',  'З' => 'Z',   'И' => 'I',
        'Й' => 'Y',   'К' => 'K',   'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',   'С' => 'S',   'Т' => 'T',
        'У' => 'U',   'Ф' => 'F',   'Х' => 'Kh',  'Ц' => 'Ts',  'Ч' => 'Ch',
        'Ш' => 'Sh',  'Щ' => 'Shch','Ъ' => '',    'Ы' => 'Y',   'Ь' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

        // ═══════════════════════════════════════════════════════════════
        // UKRAINIAN specific characters
        // ═══════════════════════════════════════════════════════════════
        'ґ' => 'g',   'є' => 'ye',  'і' => 'i',   'ї' => 'yi',
        'Ґ' => 'G',   'Є' => 'Ye',  'І' => 'I',   'Ї' => 'Yi',

        // ═══════════════════════════════════════════════════════════════
        // BELARUSIAN specific characters
        // ═══════════════════════════════════════════════════════════════
        'ў' => 'w',
        'Ў' => 'W',

        // ═══════════════════════════════════════════════════════════════
        // KAZAKH / TURKIC specific characters
        // (also used in Tatar, Bashkir, Kyrgyz, Mongolian, Yakut)
        // ═══════════════════════════════════════════════════════════════
        'ә' => 'a',   'ғ' => 'gh',  'қ' => 'q',   'ң' => 'ng',  'һ' => 'h',
        'ө' => 'o',   'ұ' => 'u',   'ү' => 'u',
        'Ә' => 'A',   'Ғ' => 'Gh',  'Қ' => 'Q',   'Ң' => 'Ng',  'Һ' => 'H',
        'Ө' => 'O',   'Ұ' => 'U',   'Ү' => 'U',

        // ═══════════════════════════════════════════════════════════════
        // TAJIK specific characters
        // ═══════════════════════════════════════════════════════════════
        'ҳ' => 'h',   'ҷ' => 'j',   'ӣ' => 'i',   'ӯ' => 'u',
        'Ҳ' => 'H',   'Ҷ' => 'J',   'Ӣ' => 'I',   'Ӯ' => 'U',

        // ═══════════════════════════════════════════════════════════════
        // SERBIAN / MACEDONIAN specific characters
        // ═══════════════════════════════════════════════════════════════
        'ђ' => 'dj',  'љ' => 'lj',  'њ' => 'nj',  'ћ' => 'c',   'џ' => 'dz',
        'ѓ' => 'gj',  'ќ' => 'kj',  'ѕ' => 'dz',
        'Ђ' => 'Dj',  'Љ' => 'Lj',  'Њ' => 'Nj',  'Ћ' => 'C',   'Џ' => 'Dz',
        'Ѓ' => 'Gj',  'Ќ' => 'Kj',  'Ѕ' => 'Dz',

        // ═══════════════════════════════════════════════════════════════
        // RUSSIAN LANGUAGES (Tatar, Bashkir, Chuvash, Yakut)
        // ═══════════════════════════════════════════════════════════════
        'җ' => 'j',   // Tatar
        'Җ' => 'J',
        'ҫ' => 's',   'ҙ' => 'z',   // Bashkir
        'Ҫ' => 'S',   'Ҙ' => 'Z',
        'ӑ' => 'a',   'ӗ' => 'e',   // Chuvash
        'Ӑ' => 'A',   'Ӗ' => 'E',
        'ҕ' => 'gh',  'ҥ' => 'ng',  // Yakut
        'Ҕ' => 'Gh',  'Ҥ' => 'Ng',

        // ═══════════════════════════════════════════════════════════════
        // SPECIAL CHARACTERS (common in 1C product names)
        // ═══════════════════════════════════════════════════════════════
        '№' => 'no',  '«' => '',    '»' => '',    '—' => '-',   '–' => '-',
        '“' => '',    '”' => '',    '‘' => '',    '’' => '',
    ];

    /**
     * Performs string transliteration.
     *
     * @param string $string Input string (may contain Cyrillic).
     * @param string $context Usage context:
     *                               - slug           — posts/products (limit 190)
     *                               - term_slug      — flat taxonomies (limit 150)
     *                               - term_slug_hier — hierarchical taxonomies (limit 50)
     *                               - attribute_slug — WooCommerce attributes (limit 60)
     *                               - filename       — file names
     *                               - sku            — SKUs
     *                               - raw            — no sanitization
     * @param string|null $locale Not used in universal dictionary, kept for backward compatibility.
     * @param int $maxLength Maximum length. 0 = auto by context.
     *
     * @return string Transliterated and sanitized string.
     */
    public function transliterate
    (
        string $string,
        string $context = 'slug',
        ?string $locale = null,
        int $maxLength = 0
    ): string
    {
        if ( '' === $string ) {
            return '';
        }

        $dictionary = $this->getDictionary();
        $maxLength  = $maxLength > 0 ? $maxLength : $this->getDefaultLimit( $context );

        // 1. Transliteration (strtr automatically processes longest keys first)
        $result = strtr( $string, $dictionary );

        // 2. Intermediate result filter (after transliteration, before sanitization)
        $result = apply_filters( 'wc1c_transliterator_before_sanitize', $result, $string, $context, $locale );

        // 3. Sanitization by context with length limit
        $result = $this->sanitize( $result, $context, $maxLength );

        // 4. Final filter
        return apply_filters( 'wc1c_transliterator_result', $result, $string, $context, $locale );
    }

    /**
     * Checks if string contains Cyrillic characters.
     *
     * Useful for deciding whether transliteration is needed
     * (e.g., during 1C import, if name is already in Latin).
     *
     * @param string $string String to check.
     *
     * @return bool
     */
    public function containsCyrillic( string $string ): bool
    {
        // U+0400–U+04FF range covers all Cyrillic alphabets
        return (bool) preg_match( '/[\x{0400}-\x{04FF}]/u', $string );
    }

    /**
     * Returns list of supported contexts.
     *
     * @return array<string>
     */
    public function getSupportedContexts(): array
    {
        return array_keys( self::DEFAULT_LIMITS );
    }

    /**
     * Gets the universal transliteration dictionary.
     *
     * @return array<string, string>
     */
    protected function getDictionary(): array
    {
        /**
         * Filter for the universal transliteration dictionary.
         *
         * Allows:
         * - Adding missing characters (e.g., for Ossetian, Chechen)
         * - Overriding standard mappings (e.g., г → h for Ukrainian)
         * - Switching to different transliteration standard (GOST, ISO, BGN)
         *
         * @param array<string, string> $dictionary Universal dictionary.
         */
        return (array) apply_filters( 'wc1c_transliterator_dictionary', $this->dictionary );
    }

    /**
     * Returns default length limit for context.
     *
     * @param string $context Context.
     * @return int Limit (0 = no limit).
     */
    protected function getDefaultLimit( string $context ): int
    {
        /**
         * Filter for default length limits.
         *
         * @param int $limit Default limit.
         * @param string $context Context.
         */
        $limit = self::DEFAULT_LIMITS[ $context ] ?? 0;

        return (int) apply_filters( 'wc1c_transliterator_default_limit', $limit, $context );
    }

    /**
     * Sanitizes string based on usage context.
     *
     * @param string $string Transliterated string.
     * @param string $context Context.
     * @param int $maxLength Maximum length (0 = no limit).
     * @return string
     */
    protected function sanitize( string $string, string $context, int $maxLength = 0 ): string
    {
        switch ( $context ) {

            case 'slug':
            case 'term_slug':
            case 'term_slug_hier':
            case 'attribute_slug':
                // Spaces and underscores → hyphens
                $string = preg_replace( '/[\s_]+/u', '-', $string );
                // Keep only Latin, digits, and hyphens
                $string = preg_replace( '/[^a-zA-Z0-9\-]/u', '', $string );
                // Remove consecutive hyphens
                $string = preg_replace( '/-+/', '-', $string );
                $string = trim( $string, '-' );
                $string = strtolower( $string );

                // Smart truncation at word boundary
                if ( $maxLength > 0 && strlen( $string ) > $maxLength ) {
                    $string = $this->truncateAtWord( $string, $maxLength );
                }

                return $string;

            case 'filename':
                // Spaces → underscores
                $string = preg_replace( '/[\s]+/u', '_', $string );
                // Keep Latin, digits, underscores, hyphens, and dots
                $string = preg_replace( '/[^a-zA-Z0-9_\-\.]/u', '', $string );
                // Remove consecutive underscores
                $string = preg_replace( '/_+/', '_', $string );
                // Remove leading/trailing dots (protection from hidden files)
                $string = trim( $string, '.' );

                return $string;

            case 'sku':
                // Remove all separators
                $string = preg_replace( '/[\s_\-]+/u', '', $string );
                // Keep only Latin and digits
                $string = preg_replace( '/[^a-zA-Z0-9]/u', '', $string );
                return strtoupper( $string );

            case 'raw':
            default:
                return $string;
        }
    }

    /**
     * Truncates string at word boundary (last hyphen).
     *
     * String must already be ASCII (no multibyte characters),
     * so fast strlen/substr are used instead of mb_*.
     *
     * @param string $string String.
     * @param int $maxLength Maximum length.
     * @return string
     */
    protected function truncateAtWord( string $string, int $maxLength ): string
    {
        if ( strlen( $string ) <= $maxLength ) {
            return $string;
        }

        $truncated  = substr( $string, 0, $maxLength );

        // Find last hyphen to avoid cutting words in half
        $lastHyphen = strrpos( $truncated, '-' );

        // If hyphen found and it's not at the start — truncate at it
        if ( $lastHyphen !== false && $lastHyphen > 0 ) {
            $truncated = substr( $truncated, 0, $lastHyphen );
        }

        return rtrim( $truncated, '-' );
    }
}