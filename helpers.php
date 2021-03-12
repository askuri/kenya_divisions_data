<?php
//////////////////// String manipulation functions ////////////////////

/**
 * Convert "THis weIrd Text" to "This Weird Text"
 * @param string $input
 * @return string
 */
function titleCase(string $input): string {
    return ucwords(strtolower($input));
}

/**
 * Capitalize after character: Convert "This-test" to "This-Test" where char = '-'
 * @param string $input
 * @param string $char
 * @return string
 */
function capitalizeAfterChar(string $input, string $char): string {
    return implode($char, array_map('ucfirst', explode($char, $input)));
}

/**
 * Removes double (or more) whitespaces by a single space.
 * Source https://stackoverflow.com/questions/2326125/remove-multiple-whitespaces
 * @param string $input
 * @return string
 */
function removeUnnecessarySpaces(string $input): string {
    return preg_replace('/\s+/', ' ', $input);
}

/**
 * Remove "sub county", "ward" and the like from the names.
 * @param $input string
 * @return string
 */
function removeDivisionNames(string $input): string {
    $input = str_ireplace(' sub county', '', $input);
    $input = str_ireplace(' sub- county', '', $input);
    $input = str_ireplace(' sub-county', '', $input);
    $input = str_ireplace(' county', '', $input);
    $input = str_ireplace(' ward', '', $input);
    return $input;
}


//////////////////// Other functions ////////////////////

/**
 * Print text to console with a new line
 * @param $text string
 */
function console(string $text): void {
    echo $text.PHP_EOL;
}

/**
 * Sort an array by its keys instead of values. Do that recursively.
 * That means, if an array contains another array, the inner array will
 * also be sorted by its keys.
 * @param $data mixed the array to be sorted
 */
function recursiveKsort(&$data) {
    if (is_array($data)) {
        ksort($data);
        foreach ($data as &$value) {
            recursiveKsort($value);
        }
    }
}
