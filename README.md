## Csscompiler sass, scss for bitrix
===========

Компонент для Bitrix. Компилирует sass(или scss) файлы в готовый css.

Можно также реализовать, например, компиляцию less файлов. Для этого нужно написать класс, например, LessCompiler. Унаследовать его от класса Compiler, реализовать в нем метод `toCss($file)` и сохранить в папке с компонентом под одноименным названием. И в вызове компонента параметром `CLASS_HANDLER` указать `LessCompiler`.


## Пример использования

```php
<?php $APPLICATION->IncludeComponent("we:csscompiler", ".default", array(
	"PATH" => "/bitrix/templates/main/sass/",
	"PATH_CSS" => "/bitrix/templates/main/",
	"FILES" => array(
		0 => "styles.sass",
	),
	"CLASS_HANDLER" => "SassCompiler",
	"USE_SETADDITIONALCSS" => "Y",
	),
	false,
    array("HIDE_ICONS" => "Y")
); ?>
```