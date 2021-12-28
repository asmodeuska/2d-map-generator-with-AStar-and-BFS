var _x;
var _y;
var timeTaken;
var startTime;
var alreadySolved = false;

$(document).ready(function () {

    $("#generate").prop("disabled", true);
    $("#solve").prop("disabled", true);

    $.ajax({
        type: "POST",
        url: "api/ajax.php",
        start_time: new Date().getTime(),
        data: { action: 'getInfo'},
        success: function (data) {
            startTime = this.start_time;
            $("#generate").removeAttr('disabled');
            $("#solve").removeAttr('disabled');
            data = JSON.parse(data);
            _x = data[0].x;
            _y = data[0].y;
            getTable();

        },
    });

    $('#generate').click(function (e) {
        e.preventDefault();
        _x = $("#x").val();
        _y = $("#y").val();
        if (_x == '' || _y == '') {
            $("#result").text("számokat kérek");
            return;
        }
        $("#generate").prop("disabled", true);
        $("#solve").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: "api/ajax.php",
            data: { action: 'create', x: _x, y: _y },
            start_time: new Date().getTime(),
            success: function (data) {
                startTime = this.start_time;
                $("#generate").removeAttr('disabled');
                $("#solve").removeAttr('disabled');
                createMap(data);
            },
        });
    });
    $('#solve').click(function (e) {
        e.preventDefault();
        if(!alreadySolved)
            solve();
    });
});

function getTable(){
    $.ajax({
        type: "POST",
        url: "api/ajax.php",
        data: { action: 'getTable'},
        success: function (data) {
            createMap(data);
        },
    });
}

function solve() {
    var start = Date.now();
    $.ajax({
        type: "POST",
        url: "api/ajax.php",
        start_time: new Date().getTime(),
        data: { action: 'solve' },
        success: function (data) {
            startTime = this.start_time;

            if (!data) {
                $("#result").text("nincs út");
                return;
            }
            data = JSON.parse(data);
            $.each(data, function (i, value) {
                setTimeout(function () {
                    x = value[0] + 1;
                    y = value[1] + 1;
                    if(i==data.length-1){
                        $("#map tr:nth-child(" + x + ") td:nth-child(" + y + ")").css("background-color", "green");
                    }
                    else{
                        $("#map tr:nth-child(" + x + ") td:nth-child(" + y + ")").css("background-color", "yellow");
                    }
                }, i * 100);
            });
            timeTaken = new Date().getTime() - startTime;
            $("#result").text("út kész " + data.length + " lépésből, "+timeTaken+"ms alatt");
            alreadySolved = true;
        },
    });
}

function createMap(data) {
    alreadySolved = false;
    var map = JSON.parse(data);
    var table_body = '<table>';
    counter = 0;
    for (var i = 0; i < _x; i++) {
        table_body += '<tr>';
        for (var j = 0; j < _y; j++) {
            field = map[counter].field;
            if (field == 8) {
                table_body += '<td class="obstacle">';
                table_body += '';
                table_body += '</td>';
            }
            else if (field == 1) {
                table_body += '<td class="start">';
                table_body += '';
                table_body += '</td>';
            }
            else if (field == 9) {
                table_body += '<td class="finish">';
                table_body += '';
                table_body += '</td>';
            }
            else {
                table_body += '<td>';
                table_body += '';
                table_body += '</td>';
            }
            counter++;
        }
        table_body += '</tr>';
    }
    table_body += '</table>';
    $('#map').html(table_body);
    timeTaken = new Date().getTime() - startTime;
    $("#result").text("map generálás kész "+timeTaken+"ms alatt");

}

function timer(){
    if (count <= 0)
    {
        clearInterval(counter);
        return;
     }
     count++;
}