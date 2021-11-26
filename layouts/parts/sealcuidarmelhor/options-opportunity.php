<?php
$app->view->enqueueScript('app', 'sealcuidarmelhor', '/js/sealcuidarmelhor/seal-certified.js');
?>

<div>
    <hr>
    <span>Selecione a oportunidade</span>
    <small class="registration-help">Escolha a Oportunidade e o campo para mostrar no certificado.</small>
    <select name="" id="selectOpportunitySeal" class="form-control">
        <option value="">--Selecione--</option>
        <?php
        dump($opportunits);
            foreach ($opportunits as $key => $value){
                echo '<option value="'.$key.'">'.$value.'</option>';
            }

        ?>
        
    </select>
</div>
<div id="divOptionFieldsSealOpportunity">
    <span>Escolha o campo</span> <br>
    <select name="" id="sealOpportunityFields">            
    </select>
</div>