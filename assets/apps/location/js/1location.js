var edit, add, view;

$(document).ready(function () {

    jqgrid(),

    (add = $("#create")).dialog({
        autoOpen: !1,
        width: 640,
        resizable: !1,
        modal: !0,
        close: function () {
            $("p.feedback", $(this)).html("").hide(), $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val(""), $(this).dialog("close")
        }
    }),

    (edit = $("#edit")).dialog({
        autoOpen: !1,
        width: 600,
        resizable: !1,
        modal: !0,
        close: function () {

            $("p.feedback", $(this)).html("").hide(), $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val(""), $(this).dialog("close")
        }
    }),
    (view = $("#view")).dialog({
        autoOpen: !1,
        width: 600,
        resizable: !1,
        modal: !0,
        close: function () {
            $("p.feedback", $(this)).html("").hide(), $('input[type="text"], input[type="hidden"], select, textarea, input[type="file"]', $(this)).val(""), $("#file_link", $(this)).remove(), $(this).dialog("close")
        }
    }), $("#section_id", "#create").change(function () {
        filter("#create", $(this).val(), "sector")
    }), $("#section_id", "#edit").change(function () {
        filter("#edit", $(this).val(), "sector")
    })
});

function jqgrid() {

    $("#grid").jqGrid({
        url: base_url + "/settings/locations/index/grid",
        datatype: "json",
        mtype: "GET",
        colNames: ["#", "Байршил", "Код", "Өргөрөг", "Уртраг"],
        colModel: [{
            name: "location_id",
            index: "location_id",
            width: 15,
            align: "center"
        }, {
            name: "location",
            index: "location",
            width: 200,
            align: "center",

          
        }, {
            name: "code",
            index: "code",
            width: 80,
            formatter: view_link
        }, {
            name: "latitude",
            index: "latitude",
            width: 80,
            formatter: view_link
        }, {
            name: "longitude",
            index: "longitude",
            width: 80,
            align: "center",
            formatter: view_link
        }],
        jsonReader: {
            page: "page",
            total: "total",
            records: "records",
            root: "rows",
            repeatitems: !1,
            id: "location_id"
        },
        pager: "#pager",
        rowNum: 20,
        rowList: [10, 20, 30, 40],
        sortname: "location_id",
        sortorder: "asc",
        viewrecords: !0,
        gridview: !0,
        caption: "Байршил",
        autowidth: !0,
        height: 500,
        width: "100%",
        editurl: "server.php",
        loadComplete: function() {
            for (var e = $(this).jqGrid("getDataIDs"), t = 0; t < e.length; t++) {
                $("#grid").jqGrid("getRowData", e[t]);
                jQuery("#" + e[t], jQuery("#grid")).addClass("context-menu")
            }
        }
    }).navGrid("#pager", {
        edit: !1,
        add: !1,
        del: !1,
        search: !0
    }), jQuery("#grid").jqGrid("filterToolbar", {
        searchOperators: !0,
        stringResult: !0,
        defaultSearch: "cn"
    })
}


function set_section() {
    var e = "",
        t = 0;
    return $("#section_id option").each(function() {
        e = $(this).text() + ":" + $(this).text(), t++
    }), e = ":Бүгд;ТТИ:ТТИ;ААЧУ:ААЧУ;Захиргаа, Хүний нөөц:Захиргаа, Хүний нөөц;Холбоо:Холбоо;Навигаци:Навигаци;Ажиглалт, автоматжуулалт:Ажиглалт, автоматжуулалт;Гэрэл суулт, цахилгаан:ГСЦ"
}

function add_modal() {
    add.dialog({
        buttons: {
            "Хадгалах": function() {
                var e = $("#create").serialize();
                $.ajax({
                    type: "POST",
                    url: base_url + "/settings/locations/index/add",
                    data: e,
                    dataType: "json",
                    async: !1,
                    success: function(e) {
                        "success" == e.status ? (add.dialog("close"), showMessage(e.message, "success"), jQuery("#grid").jqGrid("setGridParam", {
                            datatype: "json"
                        }).trigger("reloadGrid")) : $("p.feedback", add).removeClass("success, notify").addClass("error").html(e.message).show()
                    }
                })
            },
            "Хаах": function() {
                add.dialog("close")
            }
        }
    }), add.dialog("open")
}

function edit_modal(e, t) {

    var i;

    $("input[name=location_id]", edit).val(e), $.ajax({

        type: "POST",

        url: base_url + "/settings/locations/index/get",

        data: {
            id: e
        },

        dataType: "json",

        success: function(e) {

            $("#id", edit).val(e.location_id), 

            $("#location", edit).val(e.location), 
            
            $("#code", edit).val(e.code), 

            $("#latitude", edit).val(e.latitude),
            
            $("#longitude", edit).val(e.longitude),

            i = e.location
            
        }
    }).done(function() {

        edit.dialog({

            title: "Засах: " + i,

            buttons: {
                "Хадгалах": function() {
                    var e = $("#edit").serialize();
                    $.ajax({
                        type: "POST",
                        url: base_url + "/settings/locations/index/edit",
                        data: e,
                        dataType: "json",
                        async: !1,
                        success: function(e) {
                            "success" == e.status ? (edit.dialog("close"), showMessage(e.message, "success"), jQuery("#grid").jqGrid("setGridParam", {
                                datatype: "json"
                            }).trigger("reloadGrid")) : $("p.feedback", edit).removeClass("success, notify").addClass("error").html(e.message).show()
                        }
                    })
                },
                "Хаах": function() {
                    edit.dialog("close")
                }
            }
        }), edit.dialog("open")
    })
}

function view_modal(e) {
    var t;
    $.ajax({
        type: "POST",
        url: base_url + "/settings/locations/index/get/",
        data: {
            id: e
        },
        dataType: "json",
        success: function(e) {

            $("#id", view).val(e.id), 
            
            $("#section_id option[value=" + e.section_id + "]", view).attr("selected", "selected"), 
            
            $("#sector_id option[value=" + e.sector_id + "]", view).attr("selected", "selected"), 
            
            $("#first_name", view).val(e.first_name), 
            
            $("#last_name", view).val(e.last_name), 
            
            $("#username", view).val(e.username), 
            
            t = e.fullname
        }
    }).done(function() {
        view.dialog({
            title: "Инженер, техникийн ажилтан: [" + t + "]",
            buttons: {
                "Хаах": function() {
                    view.dialog("close")
                }
            }
        }), view.dialog("open")
    })
}

function filter(e, t, i) {
    $.post(base_url + "/settings/locations/index/filter", {
        id: t
    }, function(t) {
        var a = $("#" + i + "_id", e);
        if (a.prop) d = a.prop("options");
        else var d = a.attr("options");
        $("option", a).remove(), $.each(t, function(e, t) {
            d[d.length] = new Option(t, e)
        })
    })
}

function view_link(e, t, i) {
    return "<a target='_blank' onclick='view_modal(" + i.employee_id + ")'>" + e + "</a>"
}

