<?php

namespace AlexeyMezenin\LaravelRussianSlugs;

/**
 * Class Slugs
 * @package AlexeyMezenin\LaravelRussianSlugs
 */
class Slugs
{
    /**
     * @var string URL converted to slug
     */
    public $slug;

    /**
     * @var string Delimiter: '-' or '_'
     */
    private $delimiter;

    /**
     * @var integer Type of returned URL
     */
    private $urlType;

    /**
     * @var boolean Should capitals be kept?
     */
    private $keepCapitals;

    /**
     * Slugs constructor.
     *
     * @param string $stringToSlug Original string which will be converted to a slug
     * @param string $delimiter Slug delimiter '-' or '_'
     * @param int $urlType Type of returned URL: 1 is for URL with russian letters, 2 is for Translit
     * @param boolean $keepCapitals Should capitals be kept?
     */
    public function __construct($stringToSlug, $delimiter = null, $urlType = null, $keepCapitals = null)
    {
        $this->slug = $stringToSlug;
        $this->delimiter = is_null($delimiter) ? config('seoslug.delimiter') : $delimiter;
        $this->urlType = is_null($urlType) ? config('seoslug.urlType') : $urlType;
        $this->keepCapitals = is_null($keepCapitals) ? config('seoslug.keepCapitals') : $keepCapitals;
    }

    /**
     * Build a slug based on config.
     *
     * @param string $stringToSlug Original string which will be converted to a slug
     * @param string $delimiter Slug delimiter '-' or '_'
     * @param int $urlType Type of returned URL: 1 is for URL with russian letters, 2 is for Translit
     * @param boolean $keepCapitals Should capitals be kept?
     * @return string
     */
	public function build($stringToSlug, $delimiter = null, $urlType = null, $keepCapitals = null)
    {
        $this->__construct($stringToSlug, $delimiter, $urlType, $keepCapitals);

        $this->replaceSpacesWithDelimiter()
             ->removeInappropriateSymbols()
             ->toLower()
             ->toTranslit();

        return $this->slug;
    }

    /**
     * Remove all symbols except russian and latin letters, numbers, '-' and '_' in $this->slug
     *
     * @return $this
     */
    public function removeInappropriateSymbols(){
        $this->slug = preg_replace('/[^0-9??-????a-z_-]/iu', '', $this->slug);

        return $this;
    }

    /**
     * Replace spaces with delimiter in $this->slug
     * @return $this
     */
    public function replaceSpacesWithDelimiter(){
        // Replace multiple spaces with single one and trim slug
        $this->slug = trim(preg_replace('!\s+!', ' ', $this->slug));

        $this->slug = str_replace(' ', $this->delimiter, $this->slug);

        return $this;
    }

    /**
     * Convert $this->slug to lowercase when necessary
     *
     * @return $this
     */
    public function toLower(){
        if($this->keepCapitals === false){
            $this->slug = mb_strtolower($this->slug);
        }

        return $this;
    }

    /**
     * Convert $this->slug to translit using Yandex rules
     *
     * @return $this
     */
    public function toTranslit()
    {
        // If slug doesn't need to be transliterated
        if($this->urlType === 1) {
            return $this;
        }

        $replace = array(
            "??" => "a",   "??" => "b",   "??" => "v",   "??" => "g",
            "??" => "d",   "??" => "e",   "??" => "e",   "??" => "zh",
            "??" => "z",   "??" => "i",   "??" => "i",   "??" => "k",
            "??" => "l",   "??" => "m",   "??" => "n",   "??" => "o",
            "??" => "p",   "??" => "r",   "??" => "s",   "??" => "t",
            "??" => "u",   "??" => "f",   "??" => "kh",  "??" => "ts",
            "??" => "ch",  "??" => "sh",  "??" => "sch", "??" => "",
            "??" => "y",   "??" => "",    "??" => "e",   "??" => "yu",
            "??" => "ya",
            "??" => "A",   "??" => "B",   "??" => "V",   "??" => "G",
            "??" => "D",   "??" => "E",   "??" => "E",   "??" => "Zh",
            "??" => "Z",   "??" => "I",   "??" => "I",   "??" => "K",
            "??" => "L",   "??" => "M",   "??" => "N",   "??" => "O",
            "??" => "P",   "??" => "R",   "??" => "S",   "??" => "T",
            "??" => "U",   "??" => "F",   "??" => "Kh",  "??" => "Ts",
            "??" => "Ch",  "??" => "Sh",  "??" => "Sch", "??" => "",
            "??" => "Y",   "??" => "",    "??" => "E",   "??" => "Yu",
            "??" => "Ya",
        );

        // Create the slug
        $this->slug = iconv("UTF-8","UTF-8//IGNORE", strtr($this->slug, $replace));

        return $this;
    }

}
