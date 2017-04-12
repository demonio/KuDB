
$(function()
{
    /* MUESTRA Y OCULTA EL ASIDE */
    $('body').on('click', '[data-toggle-aside]', function(e)
    {
        e.preventDefault();
        /* ASIDE */
        var to = $(this).data('toggle-aside');

        /* OCULTA OTROS ASIDE */
        $('aside').not(to).removeClass('open s10').addClass('s0');
        $('#sidenav-overlay').hide();

        /* MUESTRA EL ASIDE SI ESTA OCULTO*/
        if ( $(to).hasClass('s0') )
        {
            $(to).removeClass('s0').addClass('open s10');
            $('#sidenav-overlay').show();
        }
        /* OCULTA EL ASIDE SI ESTA VISIBE*/
        else
        {
            $(to).removeClass('open s10').addClass('s0');
            $('#sidenav-overlay').hide();
        }
    });

    /* OCULTA EL ASIDE PINCHANDO EN EL OVERLAY O EN UN ENLACE DEL ASIDE */
    $('body').on('click', '#sidenav-overlay, aside a', function()
    {
        $('aside').removeClass('open s10').addClass('s0');
        $('#sidenav-overlay').hide();
    });
});
