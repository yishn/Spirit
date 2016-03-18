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

$spirit_cache_journals = null;

function spirit_route($method, $paths, $funcs) {
    if (!is_array($paths)) $paths = [$paths];

    $paths = array_map(function($x) {
        if ($x !== '*') return trim(BASE_PATH, '/') . rtrim($x, '/');
        return $x;
    }, $paths);

    return route($method, $paths, $funcs);
}

function spirit_json($object) {
    header('Content-Type: application/json');
    echo json_encode($object, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit();
}

function spirit_journals($id = null) {
    if ($id !== null) {
        $journal = null;

        foreach (spirit_journals() as $j) {
            if ($j['id'] == $id) {
                $journal = $j;
                break;
            }
        }

        if ($journal === null) return null;

        $journal['photos'] = spirit_photos($journal);

        $dates = array_filter(array_map(function($p) { return $p['date']; }, $journal['photos']));

        if (count($dates) != 0) {
            $journal['start_date'] = min($dates);
            $journal['end_date'] = max($dates);
        }

        return $journal;
    }

    if ($spirit_cache_journals !== null)
        return $spirit_cache_journals;

    $paths = glob(CONTENT_DIR . '*', GLOB_ONLYDIR);
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
            'permalink' => BASE_PATH . $id,
            'name' => $parsedown->title
        ];
    }

    $spirit_cache_journals = $result;
    return $result;
}

function spirit_get_exif_date($path) {
    try {
        $exif = @exif_read_data($path);

        if (isset($exif['DateTimeOriginal']))
            return new DateTime($exif['DateTimeOriginal']);
    } catch (Exception $ex) { }

    return null;
}

function spirit_photos($journal) {
    $result = [];
    $path = $journal['path'];
    $i = 1;

    $imagepaths = glob($path . '/*.{jpg,jpeg,gif,png}', GLOB_BRACE);
    $previous_imageset = null;

    foreach ($imagepaths as $imagepath) {
        $without_ext = pathinfo($imagepath, PATHINFO_FILENAME);

        $photo = [
            'src' => BASE_PATH . 'photo/' . $journal['id'] . '/' . basename($imagepath),
            'download' => BASE_PATH . 'download/' . $journal['id'] . '/' . basename($imagepath),
            'id' => 'p' . $i,
            'permalink' => $journal['permalink'] . '#p' . $i
        ];

        $date = spirit_get_exif_date($imagepath);
        if ($date !== null) {
            $photo['date'] = $date;
        }

        $mdpath = $path . '/' . $without_ext . '.md';
        if (file_exists($mdpath)) {
            $parsedown = new SpiritParsedown();
            $photo['markdown'] = file_get_contents($mdpath);
            $photo['description'] = $parsedown->text($photo['markdown']);
        }

        $index = strrpos($without_ext, '.');
        if ($index !== false) {
            $photo['imageset'] = substr($without_ext, $index + 1);
            if ($previous_imageset != $photo['imageset']) {
                $photo['start_imageset'] = true;
            }

            $previous_imageset = $photo['imageset'];
        } else if (count($result) > 0 && isset($result[count($result) - 1]['imageset'])) {
            $result[count($result) - 1]['stop_imageset'] = true;
        }

        $result[] = $photo;
        $i++;
    }

    return $result;
}
