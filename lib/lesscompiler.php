<?php

namespace Olegpro\Csscompiler;

require __DIR__ . '/../libs/lessphp/lessc.inc.php';

class LessCompiler extends Compiler {

    /**
     * @var \lessc $lessc
     */
    private $lessc;

    /**
     * Constructor
     */
    public function __construct() {     
        $this->lessc = new \lessc();
        $this->lessc->setFormatter('compressed');
    }

    /**
     * Parse a scssc file to CSS
     * @param string $file path to file
     * @return string CSS
     */
    public function toCss($file) {
        $this->lessc->setImportDir(dirname($file));
        return $this->lessc->compileFile($file);
    }

}
