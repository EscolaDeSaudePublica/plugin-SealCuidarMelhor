$(document).ready(function () {
    $("#divOptionFieldsSealOpportunity").hide();
    $("#selectOpportunitySeal").change(function (e) { 
        e.preventDefault();
        var sel = $("#selectOpportunitySeal").val();
        $.ajax({
            type: "GET",
            url: MapasCulturais.baseURL + 'opportunity/allField',
            data: {id : sel},
            dataType: "json",
            success: function (response) {
               console.log(response);
               $("#divOptionFieldsSealOpportunity").show();
                $.each(response, function (i, item) {
                    console.log({i});
                    console.log(item.id);
                    $('#sealOpportunityFields').append($('<option>', { 
                        value: item.id,
                        text : item.title
                    }));
                });
               
            }
        });
    });
});