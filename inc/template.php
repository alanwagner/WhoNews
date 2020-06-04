<?php
/**
 * WhoNews : template.php
 *
 * Methods and processing used in the front template
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**
 * Check a for a value in the $_GET array
 *
 * @param string $key
 * @param string $value
 * @param boolean $asDefault
 * @return boolean
 */
function checkGet($key, $value, $asDefault = false)
{
    if ($asDefault) {
        return !isset($_GET[$key]) || $_GET[$key] === $value;
    }

    return isset($_GET[$key]) && $_GET[$key] === $value;
}
