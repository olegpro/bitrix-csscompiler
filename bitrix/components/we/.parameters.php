<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$files = array();
if (isset($arCurrentValues["PATH"]) 
    && is_dir($_SERVER["DOCUMENT_ROOT"] . $arCurrentValues["PATH"]) 
    && $handle = opendir($_SERVER["DOCUMENT_ROOT"] . $arCurrentValues["PATH"])) {
    
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
            "NAME" => "Путь к папке с файлами, которые нужно компилировать",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        
        "PATH_CSS" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => "Путь к папке, куда складывать скомпилированный css",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        
        "FILES" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => "Список файлов для компиляции",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "DEFAULT" => "",
            "VALUES" => $files,
        ),
        
        "CLASS_HANDLER" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => "PHP класс, реализующий интерфейс Compiler",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "SassHandler",
            "REFRESH" => "Y",
        ),
        
        "USE_SETADDITIONALCSS" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => "Подключать скомпилированный css файл через CMain::SetAdditionalCSS()",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
        
    ),
);

?>