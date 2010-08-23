<?php
/**
 * Copyright Craig Heydenburg 2010 - DocTastic
 *
 * DocTastic
 * Documentation Reader for Zikula Application Framework
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * @params
 * @return      array table defs
 */
function doctastic_tables()
{
    // Initialise table array
    $table = array();

    $table['doctastic'] = DBUtil::getLimitedTablename('doctastic');

    $table['doctastic_column'] = array(
        'id' => 'id',
        'modname' => 'modname',
        'navtype' => 'navtype',
        'enablelang' => 'enablelang');

    $table['doctastic_column_def'] = array(
        'id' => 'I UNSIGNED AUTO PRIMARY',
        'modname' => 'C(64) NOTNULL DEFAULT \'\'',
        'navtype' => 'I NOTNULL DEFAULT 0',
        'enablelang' => 'I NOTNULL DEFAULT 1',
    );
    $table['doctastic_primary_key_column'] = 'id';

    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition($table['doctastic_column'], '');
    ObjectUtil::addStandardFieldsToTableDataDefinition($table['doctastic_column_def']);

    return $table;
}
