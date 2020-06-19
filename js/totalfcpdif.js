function clicou(){
    document.getElementById('gif').style.display="block";
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.dropdown').hover(function(){
        $(this).addClass('fundomenu');
    }, function(){
        $(this).removeClass('fundomenu');
    });
    $('.panel-body').hover(function(){
        $(this).addClass('fundopanel');
    }, function(){
        $(this).removeClass('fundopanel');
    });
});
function voltar() {
    window.history.back()
}
function update(){
    confirm('Deseja reprocessar todas as notas?');
    clearInterval(contador2);
    clearInterval(contador1);
}
function confirm($msg){
    var modalConfirm = function(callback){
        $("#confirm").modal('show');
        $("#myModalLabel").html($msg);
        $("#modal-btn-si").on("click", function(){
            callback(true);
            $("#confirm").modal('hide');
        });
        $("#modal-btn-no").on("click", function(){
            callback(false);
            $("#confirm").modal('hide');
        });
    };
    modalConfirm(function(confirm){
        if(confirm){
            clicou();
            location.href='reprocessa861_update.php5';
        } else{
            contador(30);
        }
    });
}
function alert($msg){
    $("#alert").modal('show');
    $("#myModalLabelalert").html($msg);
    $("#atu,#atu2").remove();
    $("table").remove();
    $(".alert").remove();
    $("#tempo").remove();
    $("#modal-btn-ok").on("click", function(){
        $("#alert").modal('hide');
        location.href='totalfcpdif.php5';
    });
}
//Contador
function contador(segundos){
    contador1 = setTimeout('redireciona()', segundos*1000);
    atualiza(segundos);
}
function atualiza(segundos){
    if(segundos>=0){
        $("#tempo").html(segundos);
        segundos = segundos-1;
        contador2 = setTimeout('atualiza('+segundos+')', 1000);
    }
}
function redireciona(){
    $( "#target" ).submit();
}
contador(31);
