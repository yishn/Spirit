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

    public static function set($key, $value) {
        $settings[$key] = $value;
        
        $setting = ORM::for_table(DB_PREFIX . 'setting')->where('key', $key)->find_one();
        $setting->value = $value;
        $setting->save();
    }
}