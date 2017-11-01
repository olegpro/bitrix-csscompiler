<?php
$MESS['OP_CS_PATH'] = 'Путь к папке с файлами, которые нужно компилировать';
$MESS['OP_CS_PATH_CSS'] = 'Путь к папке, куда складывать скомпилированный css';
$MESS['OP_CS_FILES'] = 'Список файлов для компиляции';
$MESS['OP_CS_CLASS_HANDLER'] = 'PHP класс, наследуемый от класса Compiler(должен реализовывать методы toCss и getExtension)';
$MESS['OP_CS_USE_SETADDITIONALCSS'] = 'Подключать скомпилированный css файл через Main\Page\Asset::getInstance()->addCss()';
$MESS['OP_CS_REMOVE_OLD_CSS_FILES'] = 'Удалять старые скопилированные css файлы?';
$MESS['OP_CS_TARGET_FILE_MASK'] = 'Маска файла для записи css файла. (%s обязателен, он заменится на таймштамп файла)';
$MESS['OP_CS_SHOW_ERRORS_IN_DISPLAY'] = 'Выводить ошибки работы компонента на экран. Если не выбрать, то ошибки будут писаться в лог файл функцией AddMessage2Log()';
$MESS['OP_CS_ADD_CSS_TO_THE_END'] = 'Добавлять стили в конец если используется Main\Page\Asset::getInstance()->addCss()';