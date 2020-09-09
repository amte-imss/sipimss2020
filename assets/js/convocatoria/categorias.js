/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

$(function () {
    $('#categoria_texto').keyup(function () {
        keyword = document.getElementById('categoria_texto').value;
        console.log('buscando:' + keyword);
        $.ajax({
            url: site_url + '/buscador/search_categoria'
            , method: "post"
            , timeout: 200
            , data: {keyword: keyword}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
        }).done(function (response) {
            $('#categoria_autocomplete').css('display', 'block');
            $('#categoria_autocomplete').html(response);
        });
    });
});

function set_value_categoria(id, nombre){
    document.getElementById('id_categoria').value = id;
    document.getElementById('categoria_texto').value = nombre;
    $('#categoria_autocomplete').css('display', 'none');
}