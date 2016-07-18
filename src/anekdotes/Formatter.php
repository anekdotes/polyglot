<?php

namespace Anekdotes\Formatter;

use Anekdotes\Support\Str;

/**
 * Formater class, format whole Input using provided rules.
 */
class Formatter
{

    /**
     * Provided Input. Uses a key->value format.
     *
     * @var string[]
     */
    private $items;
    /**
     * Formatting Rules to be used. The key represents the key of the Input to format. The value associated to said key is a list of strings representing which rules to use to format the input's value.
     *
     * @var array[]
     */
    private $rules;

    /**
     * Generates an instance of a Formater, using the provided items and rules.
     *
     * @param string[] $items Provided Input, contains key->values pair to be formatted
     * @param array[] $rules Formatting Rules to be used. The key represents the key of the Input to format. The value associated to said key is a list of strings representing which rules to use to format the input's value.
     *
     * @return Formater Generated Formated instance
     */
    public static function make($items, $rules)
    {
        $Formater = new Formater();
        $Formater->items = $items;
        $Formater->rules = $rules;

        return $Formater;
    }

    /**
     * Format everything in Formater according to rules.
     *
     * @return string[] The input given on formatter creation, now formatted
     */
    public function format()
    {
        $mergedParams = [];
        foreach ($this->rules as $itemName => $ruleNames) {
            foreach ($ruleNames as $rule) {
                $ruleParams = explode(':', $rule);
                $rule = $ruleParams[0];
                array_splice($ruleParams, 0, 1);
                if (array_key_exists($itemName, $this->items)) {
                    $mergedParams[] = $this->items[$itemName];
                    if (count($ruleParams) > 0) {
                        $ruleParams = explode(',', $ruleParams[0]);
                        $mergedParams = array_merge($mergedParams, $ruleParams);
                    }
                    $this->items[$itemName] = call_user_func_array([$this, $rule], $mergedParams);
                } else {
                    // item doesn't exist
                }
                array_splice($mergedParams, 0, count($mergedParams));
            }
        }
        return $this->items;
    }


    /**
     * Format the postal code value into the following format : J4R 2L6.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function postalCode($value)
    {
        if (strlen($value) > 0) {
            $value = str_replace(' ', '', $value);
            $value = substr($value, 0, 3).' '.substr($value, 3, 3);
            $value = Str::upper($value);
        }

        return $value;
    }

    /**
     * Format the phone number value into the following format : (450) 748-2822.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function phoneNumber($value)
    {
        if (strlen($value) > 1) {
            $value = preg_replace("/[^\d]+/", '', $value);
            if (strlen($value) === 12) {
                $value = substr($value, 0, 3).' ('.substr($value, 3, 3).') '.substr($value, 6, 3).'-'.substr($value, 9, 3);
            } else if (strlen($value) === 11) {
                $value = substr($value, 0, 1).' ('.substr($value, 1, 3).') '.substr($value, 4, 3).'-'.substr($value, 7, 4);
            } else if (strlen($value) === 10) {
                $value = '('.substr($value, 0, 3).') '.substr($value, 3, 3).'-'.substr($value, 6, 4);
            } else if (strlen($value) === 7) {
                $value = substr($value, 0, 3).'-'.substr($value, 3, 4);
            }
        }

        return $value;
    }

    /**
     * Format the value into a floating point number : "122.2ABD" -> 122.2.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function float($value)
    {
        return floatval($value);
    }

    /**
     * Format the value into an integer : "122.2ABD" -> 122.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function int($value)
    {
        return intval($value);
    }

    /**
     * Format the value into an integer : "122.2ABD" -> 122.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function integer($value)
    {
        return static::int($value);
    }

    /**
     * Format the received value into a datetime string. Currently only checks if the value is empty.
     *
     * @todo Format the actual string depending on the received object.
     *
     * @param string $value The input string to format
     *
     * @return string The formatted value
     */
    public static function datetime($value)
    {
        return $value == '' ? null : $value;
    }
}
