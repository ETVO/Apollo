var cells = document.getElementsByClassName("cell");
var dyntable = document.getElementsByClassName("dyntable")[0];


var rpadding = document.getElementById("rpadding");
var padding = document.getElementById("padding");
var stdpaddingvalue = 10;

rpadding.onclick = function () {
    padding.value = stdpaddingvalue;
    padding.oninput();
}

padding.oninput = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "padding: " + padding.value + "px";
    }
}

var rmargin = document.getElementById("rmargin");
var margin = document.getElementById("margin");
var stdmarginvalue = 5;

rmargin.onclick = function () {
    margin.value = stdmarginvalue;
    margin.oninput();
}

margin.oninput = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "margin-top: " + margin.value + "px";
        cells[i].style.cssText += "margin-left: " + margin.value + "px";
    }
}

var rfontsize = document.getElementById("rfontsize");
var fontsize = document.getElementById("fontsize");
var stdfontsizevalue = 1.2;

rfontsize.onclick = function () {
    fontsize.value = stdfontsizevalue;
    fontsize.oninput();
}

fontsize.oninput = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "font-size: " + fontsize.value + "em";
    }
}

var rcellback = document.getElementById("rcellback");
var cellback = document.getElementById("cellback");
var stdcellbackvalue = "ffffff";

rcellback.onclick = function () {
    cellback.jscolor.fromString(stdcellbackvalue);
    cellback.onchange();
}

cellback.onchange = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "background: #" + cellback.value;
    }
}

var rcolor = document.getElementById("rcolor");
var color = document.getElementById("color");
var stdcolorvalue = "242424";

rcolor.onclick = function () {
    color.jscolor.fromString(stdcolorvalue);
    color.onchange();
}

color.onchange = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "color: #" + color.value;
    }
}

var rborder = document.getElementById("rborder");
var border = document.getElementById("border");
var stdbordervalue = "dddddd";

rborder.onclick = function () {
    border.jscolor.fromString(stdbordervalue);
    border.onchange();
}

border.onchange = function() {
    for(var i = 0; i < cells.length; i++) 
    {
        cells[i].style.cssText += "border-color: #" + border.value;
    }
}

// var rbackground = document.getElementById("rbackground");
// var background = document.getElementById("background");
// var stdbackgroundvalue = "ffffff";

// rbackground.onclick = function () {
//     background.jscolor.fromString(stdbackgroundvalue);
//     background.onchange();
// }

// background.onchange = function() {
//     for(var i = 0; i < cells.length; i++) 
//     {
//         document.body.style.cssText += "background-color: #" + background.value + "!important";
//     }
// }


function rtudo() {
    rborder.onclick();
    rcellback.onclick();
    rcolor.onclick();
    rfontsize.onclick();
    rmargin.onclick();
    rpadding.onclick();
}

function imprimir() {
    var opts = document.getElementById("opts");
    var settings = document.getElementById("settings");

    opts.style.display = "none";
    settings.style.display = "none";

    print();

    opts.style.display = null;
    settings.style.display = null;
}