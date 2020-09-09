function tipo_convocatoria(item) {
    var value = item.value;
    switch (value) {
        case "1":
            $('#nivel_geografico').css('display', 'block');
            ocultar(2);
            mostrar(1);
            break;
        case "2":
            $('#nivel_geografico').css('display', 'block');
            ocultar(1);
            mostrar(2);
            break;
        default :
            ocultar_todo();
            break;
    }
}

function nivel_geografico(item) {
    var value = item.value;
    switch (value) {
        case "1":
            $('#regiones').css('display', 'block');
            $('#delegaciones').css('display', 'none');
            break;
        case "2":
            $('#delegaciones').css('display', 'block');
            $('#regiones').css('display', 'none');
            break;
        default :
            $('#regiones').css('display', 'none');
            $('#delegaciones').css('display', 'none');
            break;
    }
}

function mostrar(index) {
    $('#visualization' + index).css('display', 'block');
    $('#tabla' + index).css('display', 'block');
    $('#pagi').css('display', 'block');
    //$('#boton_nuevo' + index).css('display', 'block');
}

function ocultar_todo() {
    $('#nivel_geografico').css('display', 'none');
    //$('#visualization1').css('display', 'none');
    //$('#visualization2').css('display', 'none');
    $('#regiones').css('display', 'none');
    $('#delegaciones').css('display', 'none');
    $('#tabla1').css('display', 'none');
    $('#tabla2').css('display', 'none');
    //$('#boton_nuevo1').css('display', 'none');
    //$('#boton_nuevo2').css('display', 'none');
    $('#pagi').css('display', 'none');
}

function ocultar(index) {
    //$('#visualization' + index).css('display', 'none');
    $('#tabla' + index).css('display', 'none');
    $('#pagi').css('display', 'none');
   // $('#boton_nuevo' + index).css('display', 'none');
}

// DOM element where the Timeline will be 
$(function () {
    $('.fecha').datepicker();
    // get selected item count from url parameter
    var count = 10;

    // create groups
    var groups = new vis.DataSet([
        {id: 1, content: 'Región 1'},
        {id: 2, content: 'Región 2'},
        {id: 3, content: 'Región 3'},
        {id: 4, content: 'Región 4'}
    ]);

    // create items
    var items = new vis.DataSet();

    var order = 1;
    var truck = 1;
    for (var j = 0; j < 4; j++) {
        var date = new Date();
        for (var i = 0; i < count / 4; i++) {
            date.setHours(date.getHours() + 4 * (Math.random() < 0.2));
            var start = new Date(date);

            date.setHours(date.getHours() + 2 + Math.floor(Math.random() * 4));
            var end = new Date(date);

            items.add({
                id: order,
                group: truck,
                start: start,
                end: end,
                content: 'Convocatoria ' + order
            });

            order++;
        }
        truck++;
    }

    // specify options
    var options = {
        stack: true,
        start: new Date(),
        end: new Date(1000 * 60 * 60 * 24 + (new Date()).valueOf()),
        editable: false,
        margin: {
            item: 10, // minimal margin between items
            axis: 5   // minimal margin between items and the axis
        },
        orientation: 'top'
    };

    // create a Timeline
    var container = document.getElementById('visualization1');
    timeline = new vis.Timeline(container, null, options);
    timeline.setGroups(groups);
    timeline.setItems(items);

    timeline.on('doubleClick', function (properties) {
         $("#myModal").modal() 
         console.log(properties);
    });
});
