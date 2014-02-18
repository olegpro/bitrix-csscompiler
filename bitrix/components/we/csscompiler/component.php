<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//return '';

define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"]);

$path_to_files      = isset($arParams["PATH"]) && strlen(trim($arParams["PATH"])) 
                        ? preg_replace(array('~^/~', '~/$~'), '/', trim($arParams["PATH"])) 
                        : null;

$path_to_css_files  = isset($arParams["PATH_CSS"]) && strlen(trim($arParams["PATH_CSS"])) 
                        ? preg_replace(array('~^/~', '~/$~'), '/', trim($arParams["PATH_CSS"])) 
                        : SITE_TEMPLATE_PATH . "/";

$class_handler      = isset($arParams["CLASS_HANDLER"]) 
                        ? $arParams["CLASS_HANDLER"] 
                        : 'SassCompiler';
                    
                    
$target_file_mask = 'styles_%s.css';




if(file_exists(__DIR__ . '/' . $class_handler . '.php')) {

    require_once (__DIR__ . '/' . $class_handler . '.php');

    if(is_array($arParams["FILES"])) {
         
        $last_modified = time();            
        
        $modified = 0;
        foreach (scandir(ROOT_DIR . $path_to_files) as $file) {
            if ($file != "." && $file != "..") { 
                $file = ROOT_DIR . $path_to_files . $file;
                if (is_file($file) && ($last_modified = (int) @ filemtime($file)) > $modified) {
                    $modified = $last_modified;
                }
            }
        }
        
        if($modified) $last_modified = $modified;
        
        
        $target = $path_to_css_files . sprintf($target_file_mask, $last_modified);
        
        if (!file_exists(ROOT_DIR . $target)) {
            
            try{
                
                $compiler = new $class_handler;
                if($compiler instanceof Compiler){
                    $css = '';
                    foreach ($arParams["FILES"] as $file) 
                        $css .= $compiler->toCss(ROOT_DIR . $path_to_files . $file);
    
                    $compiler->saveToFile(ROOT_DIR . $target, $css);
                    $compiler->removeOldCss(ROOT_DIR .$path_to_css_files . sprintf($target_file_mask, '*'), sprintf($target_file_mask, $last_modified));
                }
            
            }catch(SassException $e){
                echo $e->getMessage();
            }catch(CompilerException $e){
                echo $e->getMessage();
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
        
        if($arParams['USE_SETADDITIONALCSS'] == 'Y'){
            $APPLICATION->SetAdditionalCSS($target);
        }else{
            echo sprintf('<link rel="stylesheet" href="%s" type="text/css">', $target);
        }
        
    }

}


$this->IncludeComponentTemplate();

?>