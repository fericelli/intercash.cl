$(".icono-izquierda").on("click",function(){
    $(".contenido-screeshot").remove();
    $("script").eq($("script").length-1).remove();
})

$(".secundaria").on("click",function(){
    $(".principal").attr("src",$(this).attr("src"));
    $(".icono-descargar").attr("href",$(this).attr("src"));
    $(".secundaria").removeClass("secundariaseleccion");

    $(this).addClass("secundariaseleccion");
})