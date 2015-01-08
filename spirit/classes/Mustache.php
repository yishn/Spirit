<?php

/**
 * Mustache class based on
 * 
 * Mustache
 * @package Simplon\Mustache
 * @author Tino Ehrich (tino@bigpun.me)
 */
class Mustache {
    /**
     * @var array
     */
    private static $data;

    /**
     * @var array
     */
    private static $templates = [];

    /**
     * @param $template
     * @param array $data
     * @param array $customParsers
     *
     * @return string
     */
    public static function render($template, array $data = [], array $customParsers = []) {
        // cache data
        self::$data = $data;

        // parse template
        $template = self::parse($template, $data);

        // run custom parsers
        $template = self::handleCustomParsers($template, $customParsers);

        return $template;
    }

    /**
     * @param $pathTemplate
     * @param array $data
     * @param array $customParsers
     * @param string $fileExtension
     *
     * @return string
     * @throws MustacheException
     */
    public static function renderByFile($pathTemplate, array $data = [], array $customParsers = [], $fileExtension = 'html') {
        // set filename
        $fileName = $pathTemplate . '.' . $fileExtension;

        // test cache
        if (isset(self::$templates[$pathTemplate]) === false) {
            // make sure the file exists
            if (file_exists($fileName) === false) {
                throw new MustacheException('Missing given template file: ' . $fileName);
            }

            // fetch template
            $template = file_get_contents($fileName);

            if ($template === false) {
                throw new MustacheException('Could not load template file: ' . $fileName);
            }

            // cache template
            self::$templates[$pathTemplate] = $template;
        }

        return self::render(self::$templates[$pathTemplate], $data, $customParsers);
    }

    /**
     * @param $template
     * @param array $data
     *
     * @return string
     */
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

            // ----------------------------------

            else if (is_bool($val) || is_array($val) && empty($val) === true) {
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

            // ----------------------------------

            elseif ($val instanceof Closure) {
                // only evaluate function if there are any
                if (strpos($template, '{{' . $key . '}}') === false) continue;

                $template = str_replace('{{{' . $key . '}}}', $val(), $template);
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val()), $template);
            }

            // ----------------------------------

            else {
                // set var: unescaped
                $template = str_replace('{{{' . $key . '}}}', $val, $template);

                // set var: escaped
                $template = str_replace('{{' . $key . '}}', htmlspecialchars($val), $template);
            }
        }

        return (string)$template;
    }

    /**
     * @param string $template
     * @param array $parsers
     *
     * @return string
     */
    private static function handleCustomParsers($template, array $parsers = []) {
        foreach ($parsers as $parser) {
            if (isset($parser['pattern']) && isset($parser['callback'])) {
                preg_match_all('|' . $parser['pattern'] . '|', $template, $match);

                if (isset($match[1][0])) {
                    $template = $parser['callback']($template, $match);
                }
            }
        }

        return (string)$template;
    }
}

/**
 * MustacheException
 * @package Simplon\Mustache
 * @author Tino Ehrich (tino@bigpun.me)
 */
class MustacheException extends Exception {}