<?php

class Setting {
    private static $settings = [];

    public static $standards = [
        'title' => '(Untitled)',
        'subtitle' => '',
        'largeImageSize' => '<700',
        'thumbSize' => '170',
        'photosPerPage' => 16,
        'albumsPerPage' => 10,
        'originalPhotoDownload' => 'true',
        'timezone' => 'UTC',
        'dateFormat' => 'jS F, Y H:i',
        'albumDateFormat' => 'F Y',

        'adminPhotosPerPage' => 50,
        'adminAlbumsPerPage' => 20,
        'albumPickerItemsPerPage' => 5,
        'readExif' => 'true',
        'version' => '0.1'
    ];

    public static function get($key) {
        if (!isset(self::$settings[$key])) {
            $results = ORM::for_table(DB_PREFIX . 'setting')->find_many();

            foreach ($results as $row) {
                self::$settings[$row->key] = $row->value;
            }
        }

        if (!isset(self::$settings[$key]) && array_key_exists($key, self::$standards)) {
            self::set($key, self::$standards[$key]);
        }

        return self::$settings[$key];
    }

    public static function set($key, $value) {
        self::$settings[$key] = $value;
        
        $setting = ORM::for_table(DB_PREFIX . 'setting')->where('key', $key)->find_one();
        if ($setting === false) $setting = ORM::for_table(DB_PREFIX . 'setting')->create();

        $setting->key = $key;
        $setting->value = $value;
        $setting->save();
    }

    public static function as_array() {
        self::get('title');
        return array_merge(self::$standards, self::$settings);
    }
}