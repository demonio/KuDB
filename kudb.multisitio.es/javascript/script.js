
$(document).ajaxStart(function()
{
});
$(document).ajaxComplete(function()
{
    materialize();
});

$(window).resize(function()
{
});

$(window).scroll(function()
{

});

$(function()
{
    /* MATERIALIZECSS */
    materialize();

    /* MUESTRA Y OCULTA ALGO */
    $('body').on('click', '[data-toggle]', function()
    {
        var to = $(this).data('toggle');
        $(to).toggle();
    });

    /* UN CLICK AL OVERLAY CIERRA ASIDEs Y MODALs */
    $('body').on('click', '#sidenav-overlay', function()
    {
        $(this).hide();
        $('aside').hide();
    });
});
