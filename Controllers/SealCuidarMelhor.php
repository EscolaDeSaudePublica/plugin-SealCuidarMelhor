<?php
namespace SealCuidarMelhor\Controllers;

require PLUGINS_PATH.'SealCuidarMelhor/vendor/autoload.php';
require PLUGINS_PATH.'SealCuidarMelhor/vendor/dompdf/dompdf/src/FontMetrics.php';
use DateTime;
use \MapasCulturais\App;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDFReport\Entities\Pdf as EntitiesPdf;
use Mpdf\Mpdf;

class Selo extends \MapasCulturais\Controller{
    
    function GET_imprimirCertificado() {
        dump('aqui');
    }
}
