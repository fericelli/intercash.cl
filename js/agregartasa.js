$(".icono-izquierda").on("click",function(){
    $(this).parent().remove();
    $("script").eq($("script").length-1).remove();
})