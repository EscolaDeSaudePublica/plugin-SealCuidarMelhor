<?php
$app->view->enqueueScript('app', 'sealcuidarmelhor', '/js/sealcuidarmelhor/seal-certified.js');
?>

?>
<div>
    <h4>Selecione a oportunidade</h4>
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