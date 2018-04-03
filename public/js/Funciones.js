function MsjError(texto)
{
    $.Notify({
        caption: "Error",
        content: texto,
        icon: "<span class='mif-cancel'></span>",
        type: "alert"
    });
}
function MsjCorrecto(texto)
{
    $.Notify({
        caption: "Correcto",
        content: texto,
        icon: "<span class='mif-checkmark'></span>",
        type: "success"
    });
}