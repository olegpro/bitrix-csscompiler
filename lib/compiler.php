<?php
/**
 * Created by olegpro.ru
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 05.09.14 1:00
 */

namespace Olegpro\Csscompiler;

use \Bitrix\Main\SystemException as SystemException;

abstract class Compiler
    implements CompilerInterface
{

    /**
     * Class compiler
     */
    private $compiler;


    /**
     * @param string $target
     * @param string $content
     * @throws \Bitrix\Main\SystemException
     */
    public function saveToFile($target, $content)
    {
        if (@ file_put_contents($target, $content) !== false) {
            @ chmod($target, 0666);
        } else {
            throw new SystemException(sprintf('Cannot write file: %s', $target));
        }
    }


    /**
     * @param string $pattern
     * @param string $currentCss
     */
    public function removeOldCss($pattern, $currentCss)
    {
        foreach (glob($pattern) as $filename) {
            if (is_file($filename) && ($basename = pathinfo($filename, PATHINFO_BASENAME)) != $currentCss) {
                @ unlink($filename);
            }
        }
    }

    /**
     * Clear cache composite site
     * @return void
     */
    public function clearAllCHTMLPagesCache()
    {
        \CHTMLPagesCache::CleanAll();
        \CHTMLPagesCache::writeStatistic(0, 0, 0, 0, 0);
    }

}


interface CompilerInterface {

    /**
     * @param string $file
     */
    public function toCss($file);

    /**
     * @return string
     */
    public static function getExtension();

}