<?php
/**
 * Created by olegpro.ru
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 03.07.14 23:33
 */

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\SystemException as SystemException;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class OlegproCSSCompilerComponent extends CBitrixComponent
{

    /**
     * Check Required Modules
     * @throws Exception
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule("olegpro.csscompiler"))
            throw new SystemException(Loc::getMessage("CVP_OLEGPRO_CSSCOMPILER_MODULE_NOT_INSTALLED"));
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

        $params['REMOVE_OLD_CSS_FILES'] = ($params['REMOVE_OLD_CSS_FILES'] == 'Y');

        $params['FILES'] = is_array($params['FILES']) ? $params['FILES'] : array();

        $params['PATH_TO_FILES'] = isset($params["PATH"]) && strlen(trim($params["PATH"]))
            ? preg_replace(array('~^/~', '~/$~'), '/', trim($params["PATH"]))
            : null;

        $params['PATH_TO_FILES_CSS'] = isset($params["PATH_CSS"]) && strlen(trim($params["PATH_CSS"]))
            ? preg_replace(array('~^/~', '~/$~'), '/', trim($params["PATH_CSS"]))
            : SITE_TEMPLATE_PATH . "/";

        $params["CLASS_HANDLER"] = isset($params["CLASS_HANDLER"]) && strlen($params["CLASS_HANDLER"])
            ? $params["CLASS_HANDLER"]
            : '\Olegpro\Csscomplier\SCSSCompiler';

        $params['TARGET_FILE_MASK'] = trim($params['TARGET_FILE_MASK']);
        if (!strlen($params['TARGET_FILE_MASK'])) {
            $params['TARGET_FILE_MASK'] = 'styles_%s.css';
        }

        return $params;
    }

    public function checkHandlerClass()
    {
        if (!class_exists($this->arParams['CLASS_HANDLER'])) {
            throw new SystemException(sprintf("Class '%s' doesn't exist.", $this->arParams['CLASS_HANDLER']));
        } elseif (!($this->arParams['CLASS_HANDLER'] instanceof \Olegpro\Csscompiler\Compiler)) {
            throw new SystemException(sprintf("Class '%s' is not a subclass of '\Olegpro\Csscomplier\Compiler'", $this->arParams['CLASS_HANDLER']));
        }
    }

    /**
     * Start Component
     */
    public function executeComponent()
    {
        global $APPLICATION;
        try {

            $this->checkModules();
            $this->checkHandlerClass();

            $last_modified = time();

            $modified = 0;
            foreach (scandir($_SERVER["DOCUMENT_ROOT"] . $this->arParams["PATH_TO_FILES"]) as $file) {
                if ($file != "." && $file != "..") {
                    $file = $_SERVER["DOCUMENT_ROOT"] . $this->arParams["PATH_TO_FILES"] . $file;
                    if (is_file($file) && ($last_modified = (int)@ filemtime($file)) > $modified) {
                        $modified = $last_modified;
                    }
                }
            }

            if ($modified) $last_modified = $modified;

            $target = $this->arParams["PATH_TO_FILES_CSS"] . sprintf($this->arParams['TARGET_FILE_MASK'], $last_modified);

            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $target)) {

                /** @var \Olegpro\Csscompiler\Compiler $compiler */
                $compiler = new $this->arParams['CLASS_HANDLER'];

                $css = '';
                foreach ($this->arParams["FILES"] as $file) {
                    $css .= $compiler->toCss($_SERVER["DOCUMENT_ROOT"] . $this->arParams["PATH_TO_FILES"] . $file);
                }

                $compiler->saveToFile($_SERVER["DOCUMENT_ROOT"] . $target, $css);

                if ($this->arParams['REMOVE_OLD_CSS_FILES']) {
                    $compiler->removeOldCss($_SERVER["DOCUMENT_ROOT"] . $this->arParams["PATH_TO_FILES_CSS"] . sprintf($this->arParams['TARGET_FILE_MASK'], '*'), sprintf($this->arParams['TARGET_FILE_MASK'], $last_modified));
                }

                if (\CHTMLPagesCache::IsCompositeEnabled()) {
                    $compiler->clearAllCHTMLPagesCache();
                }

            }

            if ($this->arParams['USE_SETADDITIONALCSS'] == 'Y') {
                $APPLICATION->SetAdditionalCSS($target);
            } else {
                echo sprintf('<link rel="stylesheet" href="%s" type="text/css">', $target);
            }

        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }

    }

}