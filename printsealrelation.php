
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

ob_start();

$mpdf->SetTitle('Mapa da Saúde - Relatório');
$stylesheet = file_get_contents(PLUGINS_PATH.'SealCuidarMelhor/assets/css/sealcuidarmelhor/styles.css');
$seal = $app->repo('SealRelation')->find($id);
$idOp = $seal->seal->opportunity; 


if($seal->owner_relation instanceof \MapasCulturais\Entities\Agent) {

    if(isset($idOp) && !empty($idOp)) {
        $opportunity = $app->repo('Opportunity')->find($idOp);
        $registration = $app->repo('Registration')->findBy([
                                                    'opportunity' => $opportunity,
                                                    'owner' => $seal->owner_relation
        ]);

        $field = "field_{$seal->seal->field}";
        $regMeta = $app->repo('RegistrationMeta')->findBy([
            'owner' => $registration,
            'key' => $field
        ]);
    }
}
include "cuidarMelhor.php";
$footer = '<img src="'.PLUGINS_PATH.'SealCuidarMelhor/assets/img/sealcuidarmelhor/rodape.png'.'" style="width: 795px;">';
                
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footer, 'E');
$mpdf->writingHTMLfooter = true;
$mpdf->AddPage('', // L - landscape, P - portrait 
                '', '', '', '',
                0, // margin_left
                0, // margin right
                15, // margin top
                0, // margin bottom
                0, // margin header
                0
            ); // margin footer
$html = ob_get_clean();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output();
exit;

?>
