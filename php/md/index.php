<?php
require_once '../../Markdown/php/Michelf/Markdown.inc.php';

spl_autoload_register(function($class){
    require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});
use \Michelf\Markdown;
$text = file_get_contents('./test.md');
$html = Markdown::defaultTransform($text);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>test</title>
    </head>
    <body>
        <?php
            echo $html;
        ?>
    </body>
</html>