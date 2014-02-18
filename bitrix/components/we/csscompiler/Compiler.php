<?php

/**
 * Basic class for all compilers.
 * @package csscompiler
 * @author Oleg Maximenko <oleg.39style@gmail.com>
 */

abstract class Compiler{


    /**
     * @param string $file
     */
    abstract public function toCss($file);


    /**
     * @param string $target
     * @param string $content
     */
    public function saveToFile($target, $content) {
        if (@ file_put_contents($target, $content) !== false) {
            @ chmod($target, 0666);
        } else {
            throw new CompilerException(sprintf('Cannot write file: %s', $target));
        }
    }
    
    
    /**
     * @param string $pattern
     * @param string $current_css
     */
     public function removeOldCss($pattern, $current_css){
        foreach (glob($pattern) as $filename) {
            if(is_file($filename) && ($basename = pathinfo($filename, PATHINFO_BASENAME)) != $current_css){
                @ unlink($filename);
            }
        }
     }
     

    /**
     * @param string $css
     * @return string $css
     */
    protected function prepareCss($css){
        
        $css = preg_replace_callback('/url\(\s*([^\)]+)\s*\)/',
            function ($match) use ($css) {
                $file = $match[1];
                $path = false;
                
                if (preg_match('~^/.+\.(gif|png|jpe?g)$~i', $file, $match)) {
                    $path = SITE_TEMPLATE_PATH;
                }
                
                if ($path !== false) {
                    return sprintf('url(%s)', $path . $file);
                } else {
                    return sprintf('url(%s)', $file);
                }
                
            }, $css
        );
        
        return $css;        
    }
    
}

class CompilerException extends Exception { }


?>