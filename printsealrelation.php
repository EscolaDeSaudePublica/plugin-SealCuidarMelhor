
<?php
ini_set('display_errors', true);
$plugin = $app->plugins['SealCuidarMelhor'];

require PLUGINS_PATH.'SealCuidarMelhor/vendor/autoload.php';;

use Mpdf\Mpdf;

$mpdf = new Mpdf([
    'tempDir' => dirname(__DIR__) . '/SealCuidarMelhor/vendor/mpdf/tmp',
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font' => 'arial']);

//$app->render($template);
ob_start();
// $template = PLUGINS_PATH.'SealCuidarMelhor/printsealrelation';
// $content = $app->view->fetch($template);

$mpdf->SetTitle('Mapa da Saúde - Relatório');
$stylesheet = file_get_contents(PLUGINS_PATH.'SealCuidarMelhor/assets/css/sealcuidarmelhor/styles.css');
include "cuidarMelhor.php";
$html = ob_get_clean();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output();
exit;

?>
