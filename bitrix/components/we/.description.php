<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
   "NAME" => "CSS Compiler",
   "DESCRIPTION" => "Компилирует файлы в .css",
   "PATH" => array(
      "ID" => "service",
      "CHILD" => array(
         "ID" => "service",
      )
   ),
   "AREA_BUTTONS" => array(
      array(
         'TITLE' => "Редактировать параметры компонента"
      ),
   ),
   "CACHE_PATH" => "Y",
   "COMPLEX" => "Y"
);
?>