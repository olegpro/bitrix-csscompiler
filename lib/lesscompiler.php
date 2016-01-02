<?php

namespace Olegpro\Csscompiler;

require __DIR__ . '/../libs/lessphp/lessc.inc.php';

class LessCompiler extends Compiler
{

    /**
     * @var \lessc $compiler
     */
    private $compiler;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->compiler = new \lessc();
        $this->compiler->setFormatter('compressed');
    }

    /**
     * Parse a scssc file to CSS
     * @param string $file path to file
     * @return string CSS
     */
    public function toCss($file)
    {
        $this->compiler->setImportDir(dirname($file));
        return $this->compiler->compileFile($file);
    }

    public static function getExtension()
    {
        return 'less';
    }

}
