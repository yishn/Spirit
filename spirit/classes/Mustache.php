<?php

class Mustache {
    private static $data;
    private static $cache = [];

    public static function render($template, array $data = []) {
        // cache data
        self::$data = $data;

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
            if (is_array($val) && empty($val) === false) {
                // find loops
                $template = preg_replace('|{{\^' . $key . '}}.*?{{/' . $key . '}}|s', '', $template);
                preg_match_all('|{{\#' . $key . '}}(.*?){{/' . $key . '}}|sm', $template, $foreachPattern);

                // handle loops
                if (isset($foreachPattern[1][0])) {
                    foreach ($foreachPattern[1] as $patternId => $patternContext) {
                        $loopContent = '';

                        // handle array objects
                        if (isset($val[0])) {
                            foreach ($val as $loopVal) {
                                // make simple lists available
                                if (is_array($loopVal) === false) {
                                    $loopVal = ['_' => $loopVal];
                                }

                                $loopContent .= self::parse($patternContext, $loopVal);
                            }
                        }

                        // normal array only
                        else {
                            $loopContent = self::parse($patternContext, $val);
                        }

                        // replace pattern context
                        $template = preg_replace(
                            '|' . preg_quote($foreachPattern[0][$patternId]) . '|s',
                            $loopContent,
                            $template,
                            1
                        );
                    }
                }
            }
        }

        foreach ($data as $key => $val) {
            if (is_bool($val) || is_array($val) && empty($val) === true) {
                // determine true/false
                $conditionChar = $val === true ? '\#' : '\^';
                $negationChar = $val === true ? '\^' : '\#';

                // remove bools
                $template = preg_replace('|{{' . $negationChar . $key . '}}.*?{{/' . $key . '}}|s', '', $template);
                // find bools
                preg_match_all('|{{' . $conditionChar . $key . '}}(.*?){{/' . $key . '}}|s', $template, $boolPattern);

                // handle bools
                if (isset($boolPattern[1][0])) {
                    foreach ($boolPattern[1] as $patternId => $patternContext) {
                        // parse and replace pattern context
                        $template = preg_replace(
                            '|' . preg_quote($boolPattern[0][$patternId]) . '|s',
                            self::parse($patternContext, self::$data),
                            $template,
                            1
                        );
                    }
                }
            }
        }

        foreach ($data as $key => $val) {
            if (is_array($val) || is_bool($val)) {}

            elseif ($val instanceof Closure) {
                // only evaluate function if there are any
                if (strpos($template, '{{' . $key . '}}') === false) continue;

                $template = str_replace('{{{' . $key . '}}}', $val(), $template);
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val()), $template);
            } else {
                $template = str_replace('{{{' . $key . '}}}', $val, $template);
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val), $template);
            }
        }

        return (string)$template;
    }
}