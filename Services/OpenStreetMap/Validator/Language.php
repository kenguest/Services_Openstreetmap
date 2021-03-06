<?php
/**
 * Language.php
 * 25-May-2020
 *
 * PHP Version 7
 *
 * @category Language
 * @package  Language
 * @author   Ken Guest <ken@linux.ie>
 * @license  GPL (see http://www.gnu.org/licenses/gpl.txt)
 * @version  CVS: <cvs_id>
 * @link     Language.php
 */

class Services_OpenStreetMap_Validator_Language
{

    public $valid = false;

    /**
     * __construct
     *
     * @param string $language Optional. Validated if specified.
     *
     * @return void
     */
    public function __construct($language = '')
    {
        if ($language !== '') {
            $this->validate($language);
        }
    }

    /**
     * Validate specified language.
     *
     * @param string $language ISO representation of language to validate
     *
     * @return void
     * @throws Services_OpenStreetMap_InvalidLanguageException If language invalid
     */
    public function validate($language): void
    {
        $langs = explode(",", $language);
        $this->valid = true;
        foreach ($langs as $lang) {
            if (strpos($lang, '-') !== false) {
                $subparts = explode("-", $lang);
                foreach ($subparts as $subpart) {
                    if (!$this->_validateLanguageRegex($subpart)) {
                        $this->valid = false;
                        throw new Services_OpenStreetMap_InvalidLanguageException("Language Invalid: $language");
                    }
                }
            } else {
                if (!$this->_validateLanguageRegex($lang)) {
                    $this->valid = false;
                    throw new Services_OpenStreetMap_InvalidLanguageException("Language Invalid: $language");
                }
            }
        }
    }

    /**
     * Validate a language via simple regex.
     *
     * Return true/false depending on outcome (alphabetic 1-8 chars long)
     *
     * @param string $language Language to validate.
     *
     * @return bool
     */
    private function _validateLanguageRegex(string $language): bool
    {
        return filter_var(
            $language,
            FILTER_VALIDATE_REGEXP,
            ['options' => ['regexp' => '/^[a-z]{1,8}$/i']]
        ) !== false;
    }
}
