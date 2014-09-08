<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$files = array();
if (isset($arCurrentValues["PATH"])
    && is_dir($_SERVER["DOCUMENT_ROOT"] . $arCurrentValues["PATH"])
    && $handle = opendir($_SERVER["DOCUMENT_ROOT"] . $arCurrentValues["PATH"])
) {

    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $files[$file] = $file;
        }
    }
    closedir($handle);
}


$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(

        "PATH" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_PATH'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => SITE_TEMPLATE_PATH . '/scss/',
            "REFRESH" => "Y",
        ),

        "PATH_CSS" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_PATH_CSS'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => SITE_TEMPLATE_PATH . '/',
            "REFRESH" => "Y",
        ),

        "FILES" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_FILES'),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "DEFAULT" => "",
            "VALUES" => $files,
        ),

        "CLASS_HANDLER" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_CLASS_HANDLER'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "\Olegpro\Csscompiler\SCSSCompiler",
            "REFRESH" => "Y",
        ),

        "USE_SETADDITIONALCSS" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_USE_SETADDITIONALCSS'),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),

        "REMOVE_OLD_CSS_FILES" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_REMOVE_OLD_CSS_FILES'),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),

        "TARGET_FILE_MASK" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => Loc::getMessage('OP_CS_TARGET_FILE_MASK'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "styles_%s.css",
        ),

    ),
);

?>