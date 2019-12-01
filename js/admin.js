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