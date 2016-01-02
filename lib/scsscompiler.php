<?php

namespace Olegpro\Csscompiler;

require __DIR__ . '/../libs/scssphp/scss.inc.php';

class SCSSCompiler extends Compiler
{

    /**
     * @var \Leafo\ScssPhp\Compiler $compiler
     */
    private $compiler;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->compiler = new \Leafo\ScssPhp\Compiler();

        $devEnvironment = (isset($_SERVER['ENV']) && in_array(strtolower($_SERVER['ENV']), array('dev', 'demo')));

        $this->compiler->setFormatter(
            !$devEnvironment
                ? '\Leafo\ScssPhp\Formatter\Crunched'
                : '\Leafo\ScssPhp\Formatter\Expanded'
        );

        if ($devEnvironment) {
            $this->compiler->setLineNumberStyle(\Leafo\ScssPhp\Compiler::LINE_COMMENTS);
        }
    }

    /**
     * Parse a scssc file to CSS
     * @param string $file path to file
     * @return string CSS
     */
    public function toCss($file)
    {
        $this->compiler->addImportPath(dirname($file));

        return ($css = @ file_get_contents($file)) !== false ? $this->compiler->compile($css) : '';
    }

    public static function getExtension()
    {
        return 'scss';
    }

}
