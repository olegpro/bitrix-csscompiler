<?php 

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$files = array();

if (isset($arCurrentValues['PATH'])
    && ($directory = new Bitrix\Main\IO\Directory($_SERVER['DOCUMENT_ROOT'] . $arCurrentValues['PATH']))
    && ($directory->isExists())
) {

    foreach ($directory->getChildren() as $ioEntry) {
        if ($ioEntry->isFile()) {
            $files[$ioEntry->getName()] = $ioEntry->getName();
        }
    }
}


$arComponentParameters = array(
    'GROUPS' => array(),
    'PARAMETERS' => array(

        'PATH' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_PATH'),
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '',
            'REFRESH' => 'Y',
        ),

        'FILES' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_FILES'),
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'DEFAULT' => '',
            'VALUES' => $files,
        ),

        'PATH_CSS' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_PATH_CSS'),
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '',
        ),

        'CLASS_HANDLER' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_CLASS_HANDLER'),
            'TYPE' => 'STRING',
            'VALUE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '\Olegpro\Csscompiler\SCSSCompiler',
            'REFRESH' => 'N',
        ),

        'USE_SETADDITIONALCSS' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_USE_SETADDITIONALCSS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),

        'ADD_CSS_TO_THE_END' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_ADD_CSS_TO_THE_END'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
        'REMOVE_OLD_CSS_FILES' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_REMOVE_OLD_CSS_FILES'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),

        'TARGET_FILE_MASK' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_TARGET_FILE_MASK'),
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'styles-compiled-%s.css',
        ),

        'SHOW_ERRORS_IN_DISPLAY' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('OP_CS_SHOW_ERRORS_IN_DISPLAY'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),

    ),
);

?>