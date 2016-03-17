<?php

class SpiritParsedown extends Parsedown {
    protected function blockHeader($line) {
        $block = parent::blockHeader($line);

        if (!$block) return;
        if ($block['element']['name'] != 'h1') return $block;

        $text = $block['element']['text'];
        $this->title = $text;

        return $block;
    }
}

function spirit_route($method, $paths, $funcs) {
    if (!is_array($paths)) $paths = [$paths];

    $paths = array_map(function($x) {
        if ($x !== '*') return trim(BASE_PATH, '/') . rtrim($x, '/');
        return $x;
    }, $paths);

    return route($method, $paths, $funcs);
}

function spirit_journals($id = null) {
    if ($id !== null) {
        $journals = array_filter(spirit_journals(), function($j) use($id) {
            return $j['id'] == $id;
        });

        if (count($journals) == 0) return;

        $journal = $journals[0];
        $journal['photos'] = spirit_photos($journal['path']);

        return $journal;
    }

    $paths = glob('content/*', GLOB_ONLYDIR);
    $result = [];

    foreach ($paths as $path) {
        if ($path[0] === '_') continue;

        $mds = glob($path . '/*.md');
        if (count($mds) === 0) continue;

        $md = file_get_contents($mds[0]);
        $parsedown = new SpiritParsedown();
        $parsedown->text($md);
        if (!isset($parsedown->title)) continue;

        $id = preg_replace('/^\d+-/', '', basename($path));

        $result[] = [
            'id' => $id,
            'path' => $path,
            'name' => $parsedown->title
        ];
    }

    return $result;
}

function spirit_photos($path) {
    $result = [];

    $imagepaths = glob($path . '/*.{jpg,jpeg,gif,png}', GLOB_BRACE);

    foreach ($imagepaths as $imagepath) {
        $photo = ['path' => $imagepath];

        $mdpath = $path . '/' . pathinfo($imagepath, PATHINFO_FILENAME) . '.md';
        if (file_exists($mdpath)) {
            $parsedown = new SpiritParsedown();
            $photo['markdown'] = file_get_contents($mdpath);
            $photo['description'] = $parsedown->text($photo['markdown']);
        }

        $result[] = $photo;
    }

    return $result;
}
