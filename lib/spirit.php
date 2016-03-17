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

function spirit_journals() {
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
