var edit;

var create;

$(function() {
    jQuery("#grid").jqGrid({
        url: base_url + "/wh_settings/spare/grid",
        datatype: "json",
        mtype: "GET",
        height: "500",
        width: "1260",
        colNames: [ "№", "Төхөөрөмж", "Сэлбэг", "Парт№", "Төрөл", "Хэм.нэгж", "Үйлдвэрлэгч", "Хэсэг", "Тасаг" ],
        colModel: [ {
            name: "spare_id",
            index: "spare_id",
            search: false,
            width: 30,
            align: "center"
        }, {
            name: "equipment",
            index: "equipment",
            width: 80,
            align: "left"
        }, {
            name: "spare",
            index: "wh_spare.spare",
            width: 110,
            align: "left"
        }, {
            name: "part_number",
            index: "part_number",
            width: 60,
            align: "left"
        }, {
            name: "sparetype",
            index: "sparetype",
            width: 60,
            align: "left"
        }, {
            name: "measure",
            index: "measure",
            width: 35,
            align: "center"
        }, {
            name: "manufacture",
            index: "manufacture",
            width: 80,
            align: "center"
        }, {
            name: "section",
            index: "section",
            width: 90,
            align: "left"
        }, {
            name: "sector",
            index: "sector.name",
            width: 80,
            align: "left"
        } ],
        jsonReader: {
            page: "page",
            total: "total",
            records: "records",
            root: "rows",
            repeatitems: false,
            id: "spare_id"
        },
        rowNum: 20,
        rowList: [ 10, 20, 30, 50, 100, 150, 200, 300 ],
        pager: "#pager",
        sortname: "spare_id",
        viewrecords: true,
        sortorder: "desc",
        caption: "Сэлбэгийн нэршил",
        loadComplete: function() {
            var rowIds = $(this).jqGrid("getDataIDs");
            for (var i = 0; i < rowIds.length; i++) {
                var rowData = $("#grid").jqGrid("getRowData", rowIds[i]);
                var trElement = jQuery("#" + rowIds[i], jQuery("#grid"));
                trElement.addClass("context-menu");
            }
        }
    });
    jQuery("#grid").jqGrid("filterToolbar", {
        stringResult: true,
        searchOnEnter: false
    });
    // edit dialog here
    edit = $("#edit-form");
    edit.dialog({
        autoOpen: false,
        width: 570,
        resizable: false,
        modal: true,
        close: function() {
            $("p.feedback", $(this)).html("").hide();
            $('input[type="text"], input[type="hidden"], select, textarea', edit).val("");
            $(this).dialog("close");
        }
    });
    // create dialog here
    create = $("#new-form");
    create.dialog({
        autoOpen: false,
        width: 570,
        resizable: false,
        modal: true,
        close: function() {
            $("p.feedback", $(this)).html("").hide();
            $('input[type="text"], input[type="hidden"], select, textarea', create).val("");
            $(this).dialog("close");
        }
    });

    // equipment bish section change hiihed

    $("#section_id", create).change(function() {
        //var filter_id = $(this).val();  
        // console.log('this_id'+$(this).val());
        filter(create, $(this).val(), 'sector');

    });


    // onchange sector create 

    $("#sector_id", create).change(function() {

       // get selected section_id by sector_id
       var section_id=  $('#section_id', create).val();

        // console.log('this_id'+section_id);

        filter_ext(create, section_id, $(this).val(), 'equipment');

    });

     $("#section_id", edit).change(function() {
        //var filter_id = $(this).val();  
        // console.log('this_id'+$(this).val());
        filter(edit, $(this).val(), 'sector');
    });


    $("#sector_id", edit).change(function() {

       // get selected section_id by sector_id
       var section_id=  $('#section_id', edit).val();

        // console.log('this_id'+section_id);

        filter_ext(edit, section_id, $(this).val(), 'equipment');

    });


});

function create_modal() {
    create.dialog({
        buttons: {
            "Хадгалах": function() {
                $("p.feedback", create).removeClass("success, error").addClass("notify").html("Утгуудыг сервер руу илгээж байна...").show();
                // logical function here
                var data = {};
                var inputs = $('input[type="text"], input[type="hidden"], select', create);
                inputs.each(function() {
                    var el = $(this);
                    data[el.attr("name")] = el.val();
                });
                // collect the form data form inputs and select, store in an object 'data'
                $.ajax({
                    type: "POST",
                    url: base_url + "/wh_settings/spare/add/",
                    data: data,
                    dataType: "json",
                    async: false,
                    success: function(json) {
                        if (json.status == "success") {
                            // амжилттай нэмсэн тохиолдолд
                            // close the dialog                                                
                            create.dialog("close");
                            showMessage(json.message, "success");
                            // amjilttai bolson tohioldold ene heseg uruu shidne
                            reload();
                        } else {
                            // ямар нэг юм нэмээгүй тохиолдолд
                            // jump to the top                
                            //$("#containerDiv").animate({ scrollTop: 0 }, "fast");
                            $("p.feedback", create).removeClass("success, notify").addClass("error").html(json.message).show();
                        }
                    }
                });
            },
            "Хаах": function() {
                create.dialog("close");
            }
        }
    });
    create.dialog("open");
}

function edit_modal(id) {
    var data = {
        spare_id: id
    };
    var status;
    $.ajax({
        type: "POST",
        url: base_url + "/wh_settings/spare/get/",
        data: data,
        dataType: "json",
        success: function(json) {
            $("#spare", edit).val(json.json.spare);
            $("#section_id option[value=" + json.json.section_id + "]", edit).attr("selected", "selected");
            $("#sector_id option[value=" + json.json.sector_id + "]", edit).attr("selected", "selected");
            $("#equipment_id option[value=" + json.json.equipment_id + "]", edit).attr("selected", "selected");
            $("span.ui-dialog-title").text("Сэлбэг:" + json.json.spare + " засах");
            $("#part_number", "#edit-form").val(json.json.part_number);
            $("#type_id option[value=" + json.json.type_id + "]", edit).attr("selected", "selected");
            $("#measure_id option[value=" + json.json.measure_id + "]", edit).attr("selected", "selected");
            $("#manufacture_id option[value=" + json.json.manufacture_id + "]", edit).attr("selected", "selected");
            $("#required_qty", edit).val(json.json.required_qty);
        }
    }).done(function() {
        edit.dialog({
            buttons: {
                "Хадгалах": function() {
                    $("p.feedback", edit).removeClass("success, error").addClass("notify").html("Утгуудыг сервер руу илгээж байна...").show();
                    // logical function here
                    var data = {};
                    var inputs = $('input[type="text"], input[type="hidden"], select', edit);
                    inputs.each(function() {
                        var el = $(this);
                        data[el.attr("name")] = el.val();
                    });
                    data["spare_id"] = id;
                    // collect the form data form inputs and select, store in an object 'data'
                    $.ajax({
                        type: "POST",
                        url: base_url + "/wh_settings/spare/edit/",
                        data: data,
                        dataType: "json",
                        success: function(json) {
                            if (json.status == "success") {
                                // амжилттай нэмсэн тохиолдолд
                                //энд үндсэн утгуудыг нэмэх болно.
                                edit.dialog("close");
                                // close the dialog                         
                                showMessage(json.message, "success");
                                // show the success message
                                reload();
                            } else if (json.status == "barcode") {
                                // ямар нэг юм нэмээгүй тохиолдолд
                                //$('p.feedback', edit).removeClass('success, notify').addClass('error').html(json.message).show();
                                var r = confirm("Баркодыг өөрчлөхдөө итгэлтэй байна уу! Хэрэв өөрчлөх бол хадгалах товчыг дарна уу!");
                                if (r) {
                                    // alert('sent to msg to the server');
                                    data["status"] = "barcode";
                                    $.ajax({
                                        type: "POST",
                                        url: base_url + "/wh_settings/spare/edit/",
                                        data: data,
                                        dataType: "json",
                                        success: function(json) {
                                            if (json.status == "success") {
                                                showMessage(json.message, "success");
                                                edit.dialog("close");
                                            } else $("p.feedback", edit).removeClass("success, notify").addClass("error").html(json.message).show();
                                        }
                                    });
                                } else alert("not printed to the server");
                            } else {
                                $("p.feedback", edit).removeClass("success, notify").addClass("error").html(json.message).show();
                            }
                        }
                    });
                },
                "Хаах": function() {
                    edit.dialog("close");
                }
            }
        });
        edit.dialog("open");
    });
}

function spare_delete(id) {
    var is_confirm = confirm("Та энэ сэлбэгийг устгахдаа итгэлтэй байна уу?");
    if (is_confirm) {
        $.ajax({
            type: "POST",
            url: base_url + "/wh_settings/spare/delete/",
            data: {
                spare_id: id
            },
            dataType: "json",
            success: function(json) {
                if (json.status == "success") {
                    // амжилттай нэмсэн тохиолдолд
                    // close the dialog                                                
                    showMessage(json.message, "success");
                    // amjilttai bolson tohioldold ene heseg uruu shidne
                    reload();
                } else showMessage(json.message, "error");
            }
        });
    }
    return true;
}

function reload() {
    jQuery("#grid").jqGrid("setGridParam", {
        datatype: "json"
    }).trigger("reloadGrid");
}

function filter(form_name, target_id, target){

    //var c_id = $('#category_id', form_name).val();
    $.post(base_url+'/wh_settings/spare/get_by', {id:target_id, target:target}, function(newOption) {
        //{id:target_id, field:target_field, table:target}
        //neelttei haalttai,
        var select = $('#'+target+'_id', form_name);

        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }

        $('option', select).remove();

        $.each(newOption, function(val, text) {

           options[options.length] = new Option(val, text);

        });

    });
}

function filter_ext(form_name,parent_id, target_id,  target){

    //var c_id = $('#category_id', form_name).val();
    $.post(base_url+'/wh_settings/spare/get_by', {id:target_id, parent_id:parent_id, target:target}, function(newOption) {
        //{id:target_id, field:target_field, table:target}
        //neelttei haalttai,
        var select = $('#'+target+'_id', form_name);

        if(select.prop) {
           var options = select.prop('options');
        }else {
           var options = select.attr('options');
        }

        $('option', select).remove();

        $.each(newOption, function(val, text) {

           options[options.length] = new Option(val, text);

        });

    });
}
