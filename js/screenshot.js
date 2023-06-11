$(".icono-izquierda").on("click",function(){
    $(this).parent().remove();
    $("script").eq($("script").length-1).remove();
})

$(".secundaria").on("click",function(){
    console.log($(this).attr("src"));
    $(".principal").attr("src",$(this).attr("src"));
    $(".icono-descargar").attr("href",$(this).attr("src"));
    $(".secundaria").removeClass("secundariaseleccion");

    $(this).addClass("secundariaseleccion");
})