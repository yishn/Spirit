<?php

class Mustache {
    private static $cache = [];

    public static function render($template, array $data = []) {
        // parse template
        $template = self::parse($template, $data);

        return $template;
    }

    public static function renderByFile($fileName, array $data = []) {
        if (isset(self::$cache[$fileName]) === false) {
            $template = file_get_contents($fileName);
            self::$cache[$fileName] = $template;
        }

        $template = self::$cache[$fileName];

        // find partials
        preg_match_all('|({{>(\S+?)}})|s', $template, $partialPattern);

        if (isset($partialPattern[2][0])) {
            foreach ($partialPattern[1] as $patternId => $patternContext) {
                // parse and replace pattern context
                $path = dirname($fileName) . '/' . $partialPattern[2][$patternId];
                $template = str_replace($patternContext, self::renderByFile($path), $template);
            }
        }

        return self::render($template, $data);
    }

    private static function parse($template, array $data = []) {
        foreach ($data as $key => $val) {
            // Find loops
            if (!is_array($val) || empty($val) !== false) continue;

            $template = preg_replace('|{{\^' . $key . '}}.*?{{/' . $key . '}}|s', '', $template);
            preg_match_all('|{{\#' . $key . '}}(.*?){{/' . $key . '}}|sm', $template, $foreachPattern);

            // Handle loops
            if (isset($foreachPattern[1][0])) {
                foreach ($foreachPattern[1] as $patternId => $patternContext) {
                    $loopContent = '';

                    if (isset($val[0])) {
                        // Handle lists
                        foreach ($val as $loopVal) {
                            $loopContent .= self::parse($patternContext, $loopVal);
                        }
                    } else {
                        // Handle object arrays
                        $loopContent = self::parse($patternContext, $val);
                    }

                    // Replace pattern context
                    $template = preg_replace(
                        '|' . preg_quote($foreachPattern[0][$patternId]) . '|s',
                        $loopContent,
                        $template,
                        1
                    );
                }
            }
        }

        foreach ($data as $key => $val) {
            // Find bools
            if (!is_bool($val) && (!is_array($val) || empty($val) !== true)) continue;

            // Determine true/false
            $conditionChar = $val === true ? '\#' : '\^';
            $negationChar = $val === true ? '\^' : '\#';

            $template = preg_replace('|{{' . $negationChar . $key . '}}.*?{{/' . $key . '}}|s', '', $template);
            preg_match_all('|{{' . $conditionChar . $key . '}}(.*?){{/' . $key . '}}|s', $template, $boolPattern);

            // Handle bools
            if (isset($boolPattern[1][0])) {
                foreach ($boolPattern[1] as $patternId => $patternContext) {
                    // Parse and replace pattern context
                    $template = preg_replace(
                        '|' . preg_quote($boolPattern[0][$patternId]) . '|s',
                        self::parse($patternContext, $data),
                        $template,
                        1
                    );
                }
            }
        }

        foreach ($data as $key => $val) {
            // Handle value types
            if (is_array($val) || is_bool($val)) continue;
            
            if ($val instanceof Closure) {
                // Only evaluate function if there are any
                if (strpos($template, '{{' . $key . '}}') === false) continue;

                $template = str_replace('{{{' . $key . '}}}', $val(), $template);
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val()), $template);
            } else {
                $template = str_replace('{{{' . $key . '}}}', $val, $template);
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val), $template);
            }
        }

        return $template;
    }
}