
function setDataeditForm() {
    var editData = JSON.parse(document.getElementById("editJobData").value);
    console.log("Edit Data:", editData);


    // async function getSubCategories(){

    //     let url = '/ajax/job/subcaegories/' + editData.category_id
    //     const res = await getAjax(url)
    //     console.log(res ,'safassasfa');
    //     document.getElementById("category").value = res.name;
    // }

    async function setCategoryInDropDown(category_id, sub_category_id){
        $('#category').val(category_id).change();
        setTimeout( () => {
            $("#subcategorys").val(sub_category_id).change()
        }, 1000)
    }

    if (editData) {
        console.log("Image:", editData.image);
        document.getElementById("title").value = editData.title;
        document.getElementById("description").value = editData.description;
        document.getElementById("requirements").value = editData.requirements;
        document.getElementById("delivery").value = editData.delivery_time;
        document.getElementById("amount").value = editData.amount;
        document.getElementById("exp").value = editData.exp_type;
        document.getElementById("priority").value = editData.priority;
        document.getElementById("budgettype").value = editData.budgettype;

        setTimeout( () => {
            setCategoryInDropDown(editData.category_id, editData.sub_category_id);
            // getSubCategories();
        }, 1000)

        setTimeout( () => {
            // set skills in the tab
            let rskla = editData.skills;
            for(var i = 0; i < rskla.length; i++){
                let skl = rskla[i].skill;
                console.log(skl);
                selectskills(skl.id, skl.name, rskla[i].job_id, false)
            }
        }, 3000)







    } else {
        return null;
    }
}

setDataeditForm();
