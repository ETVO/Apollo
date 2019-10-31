function changeParentLocation(new_location)
{
    window.parent.location.href = new_location;
}

f_exc = document.getElementById('f_exc')

f_exc.onchange = function () {
    var frm = document.getElementById('frmSearch');
    frm.submit();
}