<?php

require_once __DIR__ . '/Compiler.php';
require_once __DIR__ . '/libs/phpsass/SassParser.php';

class SassCompiler extends Compiler {

    /**
     * @var SassParser
     */
    private $sass;

    /**
     * Constructor
     * @return Sass
     */
    public function __construct() {
        
        $options = array(
            'style' => 'compact',
            'cache' => false,
            'line_numbers' => true,
            'syntax' => 'sass',
            'debug' => false,
            'callbacks' => array(),
        );
        
        $this->sass = new SassParser($options);
    }

    /**
     * Parse a Sass file to CSS
     * @param string path to file
     * @return string CSS
     */
    public function toCss($file) {
        return $this->sass->toCss($file);
    }

}
