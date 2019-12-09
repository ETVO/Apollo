function changeParentLocation(new_location)
{
    window.parent.location.href = new_location;
}

// f_exc = document.getElementById('f_exc')

// f_exc.onchange = function () {
//     var frm = document.getElementById('frmSearch');
//     frm.submit();
// }

$(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();


window.addEventListener("afterprint", afterPrint);

function imprimir()
{
    var titleprint = document.getElementsByClassName("titleprint");
    var d = document.getElementsByClassName("action");
    var options = document.getElementById("optionsContent");
    var h = document.getElementById("actions_h");
    var f = document.getElementById("actions_f");
    var input = document.getElementById("search");
    var submit = document.getElementById("submitBtn");
    
    if(input.value == "")
        input.style.display = "none";
    else
    {
        input.value = "Filtro: " + input.value;
    }
    submit.style.display = "none";

    h.style.display = "none";
    f.style.display = "none";

    options.style.display = "none";

    for(var i = 0; i < d.length; i++)
    {
        d[i].style.display = "none";
    }

    for(var i = 0; i < titleprint.length; i++)
    {
        value = titleprint[i].getAttribute("title");
        titleprint[i].innerHTML += "<br><b>(" + value + ")</b>";
    }

    print();
}

function afterPrint()
{
    window.location.href = "?after=true";
}

function submitForm(formId)
{
    var form = document.getElementById(formId);

    if(form)
    {
        form.submit();
    }
}


function atualizaNumEmp()
{
    var lblNum = document.getElementById("numero");
    var count = document.getElementsByClassName("tr");
    count = count.length;
    // if(count == NaN) count = 0;  

    if(count < 0) count = 0;
    lblNum.innerText = count + '';
    
    if(count == 0) document.getElementById("nenhum").style = null;
}