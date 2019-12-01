function submitPesquisa() {
    var frmPesquisa = document.getElementById("frmPesquisa");
    frmPesquisa.submit();
}

// var requiredField = document.getElementsByClassName('requiredField');

// for(var i = 0; i < requiredField.length; i++)
// {
//     requiredField[i].setAttribute("title", "Campo obrigatório!");
// }

function required() {
    var label = document.getElementsByTagName("label");

    for(var i = 0; i < label.length; i ++)
    {
        var lblfor = label[i].getAttribute("for");
        var input = document.getElementById(lblfor);

        if(input != null)
        {
            if(input.hasAttribute("required")){
                label[i].innerHTML = label[i].innerText + ' <b class="requiredField">*</b>';
                label[i].setAttribute("title", "Campo obrigatório!");
            }
        }
    }
}

window.onload = load();
function load() {
    required();
    verAluno();
}

function verAluno() {
    var tipo = document.getElementById("tipo");
    var ano = document.getElementById("ano");
    var ra = document.getElementById("ra");
    
    if(tipo != null)
    {
        if(tipo.value == "Aluno"){
            ano.removeAttribute("disabled");
            ano.setAttribute("required", "true");
            var label = document.getElementById("lblAno");
            label.innerHTML = 'Turma <b class="requiredField">*</b>';
            label.setAttribute("title", "Campo obrigatório!");
    
            ra.removeAttribute("disabled");
            ra.setAttribute("required", "true");
            label = document.getElementById("lblRa");
            label.innerHTML = 'RA <b class="requiredField">*</b>';
            label.setAttribute("title", "Campo obrigatório!");
        }
        else {
            ano.removeAttribute("required");
            ano.setAttribute("disabled", true);
            var label = document.getElementById("lblAno");
            label.innerHTML = 'Turma';
            label.setAttribute("title", "Apenas para alunos");
            
            ra.removeAttribute("required");
            ra.setAttribute("disabled", true);
            label = document.getElementById("lblRa");
            label.innerHTML = 'RA';
            label.setAttribute("title", "Apenas para alunos!");
        }
    }
}

var empIndisp = document.getElementsByClassName("empIndispA");

for(var i = 0; i < empIndisp.length; i++) {
    // empIndisp[i].innerText = "Visualizar";
}

var empSelec = document.getElementsByClassName("empSelecionadoA");

for(var i = 0; i < empSelec.length; i++) {
    empSelec[i].innerText = "Remover";
    empSelec[i].setAttribute("title", "Clique para remover dos livros selecionados");
}