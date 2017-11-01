<?php
/**
 * Created by olegpro.ru
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 03.07.14 23:33
 */

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\SystemException as SystemException;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class OlegproCSSCompilerComponent extends CBitrixComponent
{

    /**
     * Check Required Modules
     * @throws Exception
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('olegpro.csscompiler')) {
            throw new SystemException(Loc::getMessage('CVP_OLEGPRO_CSSCOMPILER_MODULE_NOT_INSTALLED'));
        }
    }


    /**
     * Load language file
     */
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }


    /**
     * Prepare Component Params
     */
    public function onPrepareComponentParams($params)
    {
        $params['USE_SET_ADDITIONAL_CSS'] = ($params['USE_SETADDITIONALCSS'] == 'Y');

        $params['ADD_CSS_TO_THE_END'] = isset($params['ADD_CSS_TO_THE_END']) && ($params['ADD_CSS_TO_THE_END'] == 'Y');

        $params['REMOVE_OLD_CSS_FILES'] = ($params['REMOVE_OLD_CSS_FILES'] == 'Y');

        $params['FILES'] = is_array($params['FILES']) ? $params['FILES'] : array();

        $params['PATH_TO_FILES'] = isset($params['PATH']) && strlen(trim($params['PATH']))
            ? preg_replace(array('~^/~', '~/$~'), '/', trim($params['PATH']))
            : null;

        $params['PATH_TO_FILES_CSS'] = isset($params['PATH_CSS']) && strlen(trim($params['PATH_CSS']))
            ? preg_replace(array('~^/~', '~/$~'), '/', trim($params['PATH_CSS']))
            : SITE_TEMPLATE_PATH . '/';

        $params['CLASS_COMPILER'] = isset($params['CLASS_HANDLER']) && strlen($params['CLASS_HANDLER'])
            ? $params['CLASS_HANDLER']
            : '\Olegpro\Csscompiler\SCSSCompiler';

        $params['TARGET_FILE_MASK'] = trim($params['TARGET_FILE_MASK']);
        if (!strlen($params['TARGET_FILE_MASK']) || (strpos($params['TARGET_FILE_MASK'], '%s')) === false) {
            $params['TARGET_FILE_MASK'] = 'styles-compiled-%s.css';
        }

        $params['SHOW_ERRORS_IN_DISPLAY'] = ($params['SHOW_ERRORS_IN_DISPLAY'] == 'Y');

        return $params;
    }


    /**
     * @throws SystemException
     */
    protected function checkCompilerClass()
    {
        if (!class_exists($this->arParams['CLASS_COMPILER'])) {
            throw new SystemException(sprintf('Class "%s" doesn\'t exist.', $this->arParams['CLASS_COMPILER']));
        }
    }


    /**
     * @return \Olegpro\Csscompiler\Compiler
     * @throws SystemException
     */
    protected function getCompiler()
    {
        if (!class_exists($this->arParams['CLASS_COMPILER'])) {
            throw new SystemException(sprintf('Class "%s" doesn\'t exist.', $this->arParams['CLASS_COMPILER']));
        }

        $compiler = new $this->arParams['CLASS_COMPILER'];

        if (!($compiler instanceof \Olegpro\Csscompiler\Compiler)) {
            throw new SystemException(sprintf('Class "%s" is not a subclass of \Olegpro\Csscompiler\Compiler', $this->arParams['CLASS_COMPILER']));
        }

        return $compiler;
    }


    /*
     * Check the directory needed for component
     */
    protected function checkDirs()
    {
        if (!is_readable($_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES'])) {
            throw new SystemException(Loc::getMessage('OCSS_ERROR_DIR_NOT_AVAILABLE', array('#DIR#' => $this->arParams['PATH_TO_FILES'])));
        }

        if (!is_readable($_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES_CSS'])) {
            throw new SystemException(Loc::getMessage('OCSS_ERROR_DIR_NOT_AVAILABLE', array('#DIR#' => $this->arParams['PATH_TO_FILES_CSS'])));
        } elseif (!is_writable($_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES_CSS'])) {
            throw new SystemException(Loc::getMessage('OCSS_ERROR_DIR_NOT_WRITABLE', array('#DIR#' => $this->arParams['PATH_TO_FILES_CSS'])));
        }
    }


    /**
     * Start Component
     */
    public function executeComponent()
    {

        try {

            $this->checkModules();

            $this->checkCompilerClass();

            $this->checkDirs();

            /** @var \Olegpro\Csscompiler\Compiler $compilerClass */
            $compilerClass = $this->arParams['CLASS_COMPILER'];

            $extCompiler = $compilerClass::getExtension();

            $lastModified = time();

            $modified = 0;

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES'])
            );

            foreach ($iterator as $file) {

                /** @var \SplFileInfo $file */
                if (
                    $file->isFile()
                    && $file->isReadable()
                    && $file->getExtension() === $extCompiler
                    && ($lastModified = $file->getMTime()) > $modified
                ) {
                    $modified = $lastModified;
                }
            }

            if ($modified) $lastModified = $modified;

            $target = $this->arParams['PATH_TO_FILES_CSS'] . sprintf($this->arParams['TARGET_FILE_MASK'], $lastModified);

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $target)) {

                /** @var \Olegpro\Csscompiler\Compiler $compiler */
                $compiler = $this->getCompiler();

                $css = Loc::getMessage('OCSS_FILE_AUTO_GENERATED', array('#PATH#' => $this->arParams['PATH_TO_FILES']));
                foreach ($this->arParams['FILES'] as $file) {
                    $css .= $compiler->toCss($_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES'] . $file);
                }

                if (!empty($css)) {
                    $compiler->saveToFile($_SERVER['DOCUMENT_ROOT'] . $target, $css);
                }

                if ($this->arParams['REMOVE_OLD_CSS_FILES']) {
                    $compiler->removeOldCss(
                        $_SERVER['DOCUMENT_ROOT'] . $this->arParams['PATH_TO_FILES_CSS'] . sprintf($this->arParams['TARGET_FILE_MASK'], '*'),
                        sprintf($this->arParams['TARGET_FILE_MASK'], $lastModified)
                    );
                }

                if (\CHTMLPagesCache::IsCompositeEnabled()) {
                    $compiler->clearAllCHTMLPagesCache();
                }

            }

            if ($this->arParams['USE_SETADDITIONALCSS']) {
                Main\Page\Asset::getInstance()->addCss($target, $this->arParams['ADD_CSS_TO_THE_END']);
            } else {
                echo sprintf('<link rel="stylesheet" href="%s" type="text/css">', $target);
            }

        } catch (SystemException $e) {
            if ($this->arParams['SHOW_ERRORS_IN_DISPLAY']) {
                ShowError($e->getMessage());
            } else {
                AddMessage2Log($e->getMessage(), 'olegpro.csscompiler');
            }
        }

    }

}