function toggleSectionCheckBox(obj, id) {
    var box_list = xGetElementsByTagName('input', xGetElementById(id));
    if(typeof(box_list.length)=='undefined') return;
    for(var i in box_list) {
        box_list[i].checked = obj.checked;
    }
}