function get_call() {
    var number = $('#number-phone').val();
    
    $.ajax({
        type: "POST",
        url: "handler.php",
        data: "query=c&number=" + number,
        success: function (msg) {
            $('#zvonok').html(msg);
        }
    });
}

function get_form() {
    var name = $('#form-name').val();
    var number = $('#form-phone').val();
    var text = $('#form-text').val();
    
    $.ajax({
        type: "POST",
        url: "handler.php",
        data: "query=f&number=" + number + "&name=" + name + "&text=" + text,
        success: function (msg) {
            $('#form-answer').html(msg);
        }
    });
}

