function imprime()
{
    var tabs = document.getElementsByClassName("tab");
    var num = tabs.length;

    for(var i = 0; i < num; i++)
    {
        var id = "chck" + i;
        var input = document.getElementById(id);
        input.checked = true;
    }

    var options = document.getElementById("optionsContent");
    var title = document.getElementById("title");
    var original = title.innerText;
    var principal = document.getElementById("principal");
    var footer = document.getElementById("footer");

    options.style.display = "none";
    title.style = "padding: 10px";
    title.innerText += " Apolo";
    principal.style.width = "80%";
    footer.style.display = "none";

    print();

    options.style = null;
    title.style = null;
    title.innerText = original;
    principal.style = null;
    footer.style = null;

    for(var i = 0; i < num; i++)
    {
        var id = "chck" + i;
        var input = document.getElementById(id);
        input.checked = false;
    }
}

window.addEventListener("afterprint", afterPrint);

function afterPrint()
{

    
}