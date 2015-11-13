<?php

namespace Olegpro\Csscompiler;

require __DIR__ . '/../libs/scssphp/scss.inc.php';

class SCSSCompiler extends Compiler
{

    /**
     * @var \Leafo\ScssPhp\Compiler $scssphp
     */
    private $scssphp;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scssphp = new \Leafo\ScssPhp\Compiler();

        $devEnvironment = (isset($_SERVER['ENV']) && in_array(strtolower($_SERVER['ENV']), array('dev', 'demo')));

        $this->scssphp->setFormatter(
            !$devEnvironment
                ? '\Leafo\ScssPhp\Formatter\Crunched'
                : '\Leafo\ScssPhp\Formatter\Expanded'
        );

        if ($devEnvironment) {
            $this->scssphp->setLineNumberStyle(\Leafo\ScssPhp\Compiler::LINE_COMMENTS);
        }
    }

    /**
     * Parse a scssc file to CSS
     * @param string $file path to file
     * @return string CSS
     */
    public function toCss($file)
    {
        $this->scssphp->addImportPath(dirname($file));
        return ($css = @ file_get_contents($file)) !== false ? $this->scssphp->compile($css) : '';
    }

}
