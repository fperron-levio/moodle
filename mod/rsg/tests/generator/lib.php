<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-04-07
 * Time: 11:36
 */

class mod_rsg_generator extends testing_module_generator {

    public function create_instance($record = null, array $options = null) {
        $record = (object)(array)$record;

        return parent::create_instance($record, (array)$options);
    }
}