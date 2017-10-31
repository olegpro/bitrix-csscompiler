## Csscompiler sass, scss for bitrix
===========

Компонент для Bitrix. Компилирует scss, sass или less файлы в готовый css.

Можно также реализовать, например, компиляцию less файлов. Для этого нужно написать класс, например, LessCompiler(пример класс SCSSCompiler). Унаследовать его от класса \Olegpro\Csscompiler\Compiler, реализовать в нем метод `toCss($file)` и сохранить в папке lib модуля `olegpro.csscompiler` под одноименным названием, в нижнем регистре. И в вызове компонента параметром `CLASS_HANDLER` указать `\Olegpro\Csscompiler\LessCompiler`.


## Пример использования

```php
<?$APPLICATION->IncludeComponent(
	"olegpro:olegpro.csscompiler",
	"",
	array(
		"PATH" => "/bitrix/templates/eshop_adapt_blue/scss/",
		"FILES" => array(
			0 => "style.scss",
		),
		"PATH_CSS" => "/bitrix/templates/eshop_adapt_blue/",
		"CLASS_HANDLER" => "\\Olegpro\\Csscompiler\\SCSSCompiler",
		"USE_SETADDITIONALCSS" => "Y",
		"ADD_CSS_TO_THE_END" => "Y",
		"REMOVE_OLD_CSS_FILES" => "Y",
		"TARGET_FILE_MASK" => "styles_%s.css"
	),
	false,
	array(
		"HIDE_ICONS" => "N"
	)
);?>
```

Модуль в [Маркетплейсе](http://marketplace.1c-bitrix.ru/solutions/olegpro.csscompiler/).