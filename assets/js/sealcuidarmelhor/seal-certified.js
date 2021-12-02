$(document).ready(function () {
    //$("#divOptionFieldsSealOpportunity").hide();
    $(".close-field-seal").hide();
    getNameFieldSealRelation();
    //POPULANDO O SELECT SE JÁ TIVER ALGO NO BANCO
    getOpportunity();

    $("#selectOpportunitySeal").change(function (e) {         
        e.preventDefault();
        var sel = $("#selectOpportunitySeal").val();
       getFieldOption(sel);
    });

    /**
     * SE TIVER DADOS NO DB, CARREGA OS SELECTS AO ENTRAR NA PÁGINA DE EDIÇÃO DE SELOS
     */
    function getOpportunity() {
        var idSeal = MapasCulturais.entity.id;
        $.ajax({
            type: "GET",
            url: MapasCulturais.baseURL + 'opportunity/sealOpportunity',
            data: { id: idSeal},
            dataType: "json",
            success: function (response) {
               
                $.each(response, function (indexInArray, valElement) { 
                    console.log({indexInArray})
                    if(valElement.field == 'opportunity') {
                        getFieldOption(valElement.value);
                        
                        $('#selectOpportunitySeal option[value='+valElement.value+']').attr('selected','selected');
                    }
                    if(valElement.field == 'field') {
                        console.log(valElement.value)
                        $('#sealOpportunityFields option[value='+valElement.value+']').attr('selected','selected');
                    }
                });
            }
        });
    }

    function getFieldOption(sel) {
             
        $.ajax({
            type: "GET",
            url: MapasCulturais.baseURL + 'opportunity/allField',
            data: {id : sel},
            dataType: "json",
            success: function (response) {
                $("#divOptionFieldsSealOpportunity").show();               
                $.each(response, function (i, item) {
                    $('#sealOpportunityFields').prepend($('<option>', { 
                        value: item.id,
                        text : item.title
                    }));
                });
            }
        });
    }

    $("#btnSaveOptionFieldSeal").click(function (e) { 
        e.preventDefault();
        var idOpportunity = $("#selectOpportunitySeal").val();
        var idSeal = MapasCulturais.entity.id;
        var idField = $("#sealOpportunityFields").val()
        $.ajax({
            type: "POST",
            url:  MapasCulturais.baseURL+'seal/saveCuidarMelhor',
            data: {id: idSeal, opportunity: idOpportunity, field: idField },
            dataType: "json",
            success: function (response) {
                //ATUALIZANDO A DIV
                getNameFieldSealRelation();
                if(response.status == 200) {
                    MapasCulturais.Messages.success(response.message);
                }
                
            }
        });
    });
    //NOME DO CAMPO ESCOLHIDO PELO DONO DO SELO
    function getNameFieldSealRelation() {
        var idS = MapasCulturais.entity.id;
        console.log(idS);
        $.ajax({
            type: "GET",
            url: MapasCulturais.baseURL+'seal/fieldSelect',
            data: {id: idS},
            dataType: "json",
            success: function (response) {
                if(response.error) {
                    $(".info-field-seal").html(response.data);
                }else{
                    $(".info-field-seal").html(response);
                    $(".close-field-seal").show();
                    $("#btnSaveOptionFieldSeal").hide();
                }
            }
        });
    }
});