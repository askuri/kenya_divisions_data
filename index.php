<?php
// CONFIG
define('CONF_SHP_INPUT_FILE', 'data/arc_sanitized_centroids/kenya_wards.shp');
define('CONF_SHP_OUTPUT_FILE', 'data/arc_sanitized_centroids/kenya_wards_.shp');
define('CONF_TREE_OUTPUT_FILE', 'data/arc_sanitized_centroids/kenya_wards_tree.json'); // set to false if tree should not be generated

require 'vendor/autoload.php';
require 'helpers.php';

use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;
use Shapefile\ShapefileWriter;

try {
    $inputHandle = new ShapefileReader(CONF_SHP_INPUT_FILE);

    $outputHandle = new ShapefileWriter(CONF_SHP_OUTPUT_FILE, [
        Shapefile::OPTION_EXISTING_FILES_MODE  => Shapefile::MODE_OVERWRITE,
    ]);

    $outputHandle->setShapeType($inputHandle->getShapeType()); // we don't modify the shape, so it stays the same
    $outputHandle->setPRJ($inputHandle->getPRJ()); // Projection also stays the same
    $outputHandle->setCharset($inputHandle->getCharset()); // charset should stay the same

    // Fields that should be included in the output (name is the same as in the input)
    $outputHandle->addNumericField('GID');
    $outputHandle->addNumericField('POP2009');
    $outputHandle->addCharField('COUNTY');
    $outputHandle->addCharField('SUBCOUNTY');
    $outputHandle->addCharField('WARD');
    $outputHandle->addCharField('UID');
    $outputHandle->addCharField('SCUID');
    $outputHandle->addCharField('CUID');

    $tree = [];
    echo 'Sanitization started ...';
    while ($record = $inputHandle->fetchRecord()) {
        $data = $record->getDataArray();

        // sanitize dbf attributes
        foreach ($data as $colName => &$value) {
            switch ($colName) {
                case 'COUNTY':
                case 'SUBCOUNTY':
                case 'WARD':
                    $value = titleCase($value);
                    $value = capitalizeAfterChar($value, '-');
                    $value = capitalizeAfterChar($value, '/');
                    $value = removeUnnecessarySpaces($value);
                    $value = removeDivisionNames($value);
                    $value = trim($value);
                    break;
            }
        }

        // add it to the tree
        $tree[$data['COUNTY']][$data['SUBCOUNTY']][$data['WARD']] = $record->getArray();

        // set the dbf data we just modified for the current record
        $record->setDataArray($data);

        // write the modified record into the output file
        $outputHandle->writeRecord($record);
    }
    console(' done.');

    if (CONF_TREE_OUTPUT_FILE) {
        echo 'Tree generation started ...';

        // sort the tree's keys alphabetically
        recursiveKsort($tree);

        // save the tree as json
        file_put_contents(CONF_TREE_OUTPUT_FILE, json_encode($tree, JSON_PRETTY_PRINT));

        console(' done.');
    }

} catch (ShapefileException $e) {
    // Print detailed error information
    echo "Error Type: " . $e->getErrorType()
        . "\nMessage: " . $e->getMessage()
        . "\nDetails: " . $e->getDetails();
}
