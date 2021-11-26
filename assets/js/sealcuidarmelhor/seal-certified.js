$(document).ready(function () {
   
    $("#selectOpportunitySeal").change(function (e) { 
        e.preventDefault();
        var sel = $("#selectOpportunitySeal").val();
        $.ajax({
            type: "GET",
            //url: MapasCulturais.baseURL + 'opportunity/allField',
            url: MapasCulturais.baseURL + 'api/opportunity/find&@select=id,singleUrl,category,owner.%7Bid,name,singleUrl%7D,consolidatedResult,evaluationResultString,status',
            data: {id : sel},
            dataType: "json",
            success: function (response) {
                console.log(response);
            }
        });
    });
});