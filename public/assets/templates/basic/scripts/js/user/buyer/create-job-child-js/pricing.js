

function showHideBudgetFields(type){
    console.log(type)
    if(type == 'fixed'){
        $(".budget-field-outer").css('opacity', 1)
        $(".min-max-field-outer").css('opacity', 0)
    }

    if(type == 'per_hour'){
        $(".budget-field-outer").css('opacity', 0)
        $(".min-max-field-outer").css('opacity', 1)
    }

}


const budgetType = $("#budgettype").val();
showHideBudgetFields(budgetType)

$("#budgettype").on('change', function(){
    let v = $(this).val();
    showHideBudgetFields(v)
})

