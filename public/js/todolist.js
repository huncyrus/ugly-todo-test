$(document).ready(function () {
    //
    // config
    //
    var url = 'http://test.cyrusmagus.hu/todo';


    //
    // load first everything!
    //
    function loadList() {
        $('#status').html('<i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i> Refreshing... Please stand by...');

        $.getJSON(
            url + '/getlist',
            function (result) {
                var buffer = "";
                if (result) {
                    $.each(result, function (index, val) {
                        buffer += '<li id="item-' + val.id + '" data-id="' + val.id + '">';
                        buffer += '<span class="title col-xs-8"><i class="glyphicon glyphicon-move"></i> ' + val.title + '</span>';
                        buffer += '<span class="option col-xs-4"><a hre="#" class="removeIt btn btn-default btn-danger"><i class="glyphicon glyphicon-trash"></i>Remove</a></span>';
                        buffer += '</li>';
                    });
                }
                $('ul#sortable').html(buffer);
                $('#status').html('');
            }
        );
    }


    // Add new function bind
    function addNewHelper() {
        var itemName = $('input[name=newItemname]').val();

        if (itemName) {
            $('#status').html('');
            var ajaxRequest;

            ajaxRequest= $.ajax({
                url: url + "/additem/",
                type: "post",
                data: 'title=' + itemName
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){
                $("#status").html('<span class="bg-success">Submitted successfully</span>');
                $('input[name=newItemname]').val('');
                loadList();
            });
            ajaxRequest.fail(function (response){
                $("#status").html('<span class="bg-warning">There is error while submit</span>');
                console.error(response);
            });
        } else {
            $('#status').html('<span class="bg-warning">Empty item name???</span>');
        }
    }


    //
    // INIT
    //
    loadList();


    //
    // Sortable update
    //
    $('ul').sortable({
        axis: 'y',
        stop: function (event, ui) {
            var data = $(this).sortable('serialize');
            $('#status').html('');

            var ajaxRequest;

            ajaxRequest= $.ajax({
                url: url + "/setposition/",
                type: "post",
                data: 'list=' + data
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){
                $("#status").html('<span class="bg-success">Submitted successfully</span>');
                loadList();
            });
            ajaxRequest.fail(function (response){
                $("#status").html('<span class="bg-warning">There is error while submit</span>');
            });
        }
    });

    //
    // Add new item
    //
    $('#addNewForm').submit( function (e) {
        e.preventDefault();
        addNewHelper();
    });
    $('a.addNew').on('click', function (e) {
        e.preventDefault();
        addNewHelper();
    });


    //
    // Remove item
    //
    $('#sortable').delegate('.removeIt', 'click', function (e) {
        e.preventDefault();
        var itemId = $(this).parent().parent().attr('data-id');

        if (itemId) {
            $('#status').html('');
            var ajaxRequest;

            ajaxRequest= $.ajax({
                url: url + "/removeitem/" + itemId,
                type: "get",
                data: 'id=' + itemId
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){
                $("#status").html('<span class="bg-success">Submitted successfully</span>');
                loadList();
            });
            ajaxRequest.fail(function (response){
                $("#status").html('<span class="bg-warning">There is error while submit</span>');
                console.error(response);
            });
        } else {
            $('#status').html('<span class="bg-warning">Empty item name???</span>');
        }
    });
});
