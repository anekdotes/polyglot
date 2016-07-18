<?php

namespace Anekdotes\Formatter;

use Anekdotes\Support\Str;

/**
 * Formater class, format whole Input using provided rules.
 */
class Formatter
{
    /**
     * Generates an instance of a Formater, using the provided items and rules.
     * Format everything in Formater according to rules.
     *
     * @param string[] $items Provided Input, contains key->values pair to be formatted
     * @param array[]  $rules Formatting Rules to be used. The key represents the key of the Input to format. The value associated to said key is a list of strings representing which rules to use to format the input's value.
     *
     * @return string[] The input given on formatter creation, now formatted
     */
    public static function make($items, $rules)
    {
        if (!is_array($items) || !is_array($rules)) {
            return false;
        }
        if (empty($items) || empty($rules)) {
            return false;
        }

        $mergedParams = [];
        foreach ($rules as $itemName => $ruleNames) {
            foreach ($ruleNames as $rule) {
                $ruleParams = explode(':', $rule);
                $rule = $ruleParams[0];
                array_splice($ruleParams, 0, 1);
                if (array_key_exists($itemName, $items)) {
                    $mergedParams[] = $items[$itemName];
                    if (count($ruleParams) > 0) {
                        $ruleParams = explode(',', $ruleParams[0]);
                        $mergedParams = array_merge($mergedParams, $ruleParams);
                    }
                    $items[$itemName] = call_user_func_array(['Anekdotes\Formatter\Formatter', $rule], $mergedParams);
                } else {
                    // item doesn't exist
                }
                array_splice($mergedParams, 0, count($mergedParams));
            }
        }

        return $items;
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
            $new = str_replace(' ', '', $value);
            $new = substr($value, 0, 3);
            if(strlen($value) > 3) {
                $new .= ' '.substr($value, 3, 3);
            }
            $new = Str::upper($new);
        }

        return $new;
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
            } elseif (strlen($value) === 11) {
                $value = substr($value, 0, 1).' ('.substr($value, 1, 3).') '.substr($value, 4, 3).'-'.substr($value, 7, 4);
            } elseif (strlen($value) === 10) {
                $value = '('.substr($value, 0, 3).') '.substr($value, 3, 3).'-'.substr($value, 6, 4);
            } elseif (strlen($value) === 7) {
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
