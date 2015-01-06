<?php

class Setting {
    private static $settings = [];

    public static function get($key) {
        if (!isset(self::$settings[$key])) {
            $results = ORM::for_table(DB_PREFIX . 'setting')->find_many();

            foreach ($results as $row) {
                self::$settings[$row->key] = $row->value;
            }
        }

        return self::$settings[$key];
    }
}