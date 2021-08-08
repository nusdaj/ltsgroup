function quickview_button(d, a, i) {
    var o = $(".image a", d).attr("href"),
        l = $(".image a img", d).attr("title"),
        c = productid_by_link(o),
        w = screen.width < 768 ? 1 : 0;

    $(d).append('<div class="quickmodal-mask"> <button class="on-modal ' + a + '" data-toggle="modal" data-target="#myModal">'+ decodeHtml(i) +'</button> </div>'),
     $(d).hover(function() {
        $(".quickmodal-mask", this).css({
            opacity: 1
        })
    }, function() {
        $(".quickmodal-mask", this).css({
            opacity: w
        })
    });
    
     $(".on-modal", d).click(function() {
        $(".modal-title").html(l), $(".modal-body").load("index.php?route=module/quickmodal/quickmodaldata&" + c)
    }); 
}

function decodeHtml(e){var t=document.createElement("textarea");return t.innerHTML=e,t.value}


function productid_by_link(d) {
    if ("index.php" == d.match("index.php")) {
        for (var t = d.split("&"), a = 0; a < t.length; a++)
            if ("product_id=" == t[a].match(/product_id=/i)) {
                e = t[a];
                break
            }
    } else if ("product_id=" == d.match(/product_id=/i)) {
        for (var t = d.split("&"), a = 0; a < t.length; a++)
            if ("product_id=" == t[a].match(/product_id=/i)) {
                var i = t[a].split("?");
                e = i[i.length - 1];
                break
            }
    } else var o = d.split("/"),
        l = o[o.length - 1],
        i = l.split("?"),
        c = i[0],
        e = productid_by_keyword(c);
    return e
}

function productid_by_keyword(d) {
    return $.ajax({
        url: "index.php?route=module/quickmodal/productid_by_keyword",
        type: "post",
        data: "keyword=" + d,
        dataType: "json",
        success: function(d) {
            product_id = d.success
        },
        async: !1
    }), product_id
}

function init_modal() {

    $("body").append('<div id="myModal" class="modal fade" role="dialog"> <div class="modal-dialog"> <!-- Modal content--> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Modal Header</h4> </div> <div class="modal-body"></div></div> </div> </div>');
    var d = .7 * screen.height;
    $(".modal-body").css("height", d);
}
$(document).ready(function() {
	$('.product_detail').each(function(){
	quickview_button(this,'btn-primary','Quick View');});
	init_modal();
	});