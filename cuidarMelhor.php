<?php
namespace SealCuidarMelhor\Entities;

use SealCuidarMelhor\Entities\CuidarMelhor;

$seal = $app->repo('SealRelation')->find($id);

$idOp = $seal->seal->opportunity; 

if($seal->owner_relation instanceof \MapasCulturais\Entities\Agent) {

    $msg = CuidarMelhor::message($seal, $idOp);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link type="text/css" href="bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="stylePdfReport.css" rel="stylesheet" />
    <link type="text/css" href="<?php $this->asset('css/sealcuidarmelhor/styles.css') ?>" rel="stylesheet" />

</head>
<body>

<table width="100%" class="mrg-50-left" style="height: 100px;">
    <thead>
        <tr class="">
            <td>                   
                <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/logo-saude.png'; ?>" style="float:left;"/>
            </td>
            <td>
            <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png'; ?>"
             style="margin-left: 350px;" alt="">
            </td>
        </tr>
    </thead>
</table>
<div class="container img-container">
    <div>
        <h4 class="color-label-cuidar-melhor">CERTIFICADO</h4>
    </div>
    <div class="seal-cuidar-melhor">
        <span class=""> <!-- Inline parent element -->
        <img src="<?php echo PLUGINS_PATH.'SealCuidarMelhor/assets/img/sealcuidarmelhor/cil_badge.png'; ?>" alt="">
        </span>
    </div>
</div>

<div>
    <p class="color-label-cuidar-melhor text-left mrg-50-left mrg-50-right">
    <?php if(isset($msg) && $msg !== '') {
        echo $msg;
    } ?>
    </p>
</div>
<div style=" margin-top: 136px;" class="mrg-50-left mrg-50-right">
    <table style="width: 100%;">
        <tr>
            <td  style="width: 5%;"></td>
            <td  style="width: 40%;" class="assinatura-cuidar-melhor">

                <img src="<?php echo PLUGINS_PATH.'SealCuidarMelhor/assets/img/sealcuidarmelhor/assinatura.png'; ?>" alt="">
            
            </td>
            <td style="width: 10%;" ></td>
            <td  style="width: 40%;" class="assinatura-cuidar-melhor">
               
                <img src="<?php echo PLUGINS_PATH.'SealCuidarMelhor/assets/img/sealcuidarmelhor/assinatura.png'; ?>" alt="">
                
            </td>
            <td  style="width: 5%;"></td>
        </tr>
    </table>
</div>
<div style=" margin-top: 90px;" class="mrg-50-left mrg-50-right">
    <table style="width: 100%;">
        <tr>
            <td  style="width: 30%;" >
               <span class="info-link-cuidar-melhor">
               Acesse o link abaixo ou aponte a câmera do celular ao QR code ao lado para acessar o comprovante desta declaração:
               </span>
               <label for="">
                    <a href="<?php echo $app->createUrl('seal','printsealrelation',[$relation->id]); ?>" class="link-selo-cuidar-melhor">
                        <?php echo $app->createUrl('seal','printsealrelation',[$relation->id]); ?>
                    </a>    
               </label>
            </td>
            <td style="width: 30%;" >

            </td>
            <td  style="width: 40%;" >
                <p>
                    <span class="info-link-cuidar-melhor">
                    Escola de Saúde Pública do Ceará Paulo Marcelo Martins Rodrigues Av. Antônio Justa, 3161 - Meireles • CEP: 60.165-090 Fortaleza / CE • Fone: (85) 3101.1398
                    </span>
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>


