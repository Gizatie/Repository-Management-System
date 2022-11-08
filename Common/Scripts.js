function selectDepartement(v = '') {
    // alert(v);
    $.ajax({
        type: 'POST',
        url: '../Common/Common_Calls.php?selectDepartment=departmentSelected&&Faculty=' + v,
        success: function (html) {
            $('#Department').html(html);
            $('#Department_modal').html(html);
        }
    });
}
function selectType(v = '') {

    $.ajax({
        type: 'GET',
        url: '../Common/Common_Calls.php?Type=Type&&Faculty=' + v,

        success: function (html) {
            $('#Type').html(html);
            $('#Type_modal').html(html);
        }
    });
}
function validation(serialize=''){
    const totalbirr = serialize.split("&")[2].split("=");

    if (isNaN(totalbirr[1])) {

        alert("Total can't be a string");
        return false;
    } else if (totalbirr[1] < 0) {
        alert("Total can't be Negative");
        return false;
    }
}