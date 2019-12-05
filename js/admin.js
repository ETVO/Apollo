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
    var d = document.getElementsByClassName("action");
    var options = document.getElementById("optionsContent");
    var h = document.getElementById("actions_h");
    var f = document.getElementById("actions_f");
    var input = document.getElementById("search");
    var submit = document.getElementById("submit");
    
    input.style.display = "none";
    submit.style.display = "none";

    h.style.display = "none";
    f.style.display = "none";

    options.style.display = "none";

    for(var i = 0; i < d.length; i++)
    {
        d[i].style.display = "none";
    }

    print();
}

function afterPrint()
{
    window.location.href = "?after=true";
}