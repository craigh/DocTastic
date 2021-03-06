Event.observe(window, 'load', doctastic_help_load);

function doctastic_help_load()
{
    if ($('doctastic_help_collapse')) {
        doctastic_help_init();
    }
}


function doctastic_help_init()
{
    $('doctastic_help_collapse').observe('click', doctastic_help_click);
    if ($('doctastic_help_container').style.display != "none") {
        $('doctastic_help_showhide').update(Zikula.__('Help','module_doctastic'));
        $('doctastic_help_container').hide();
    }
}

function doctastic_help_click()
{
    if ($('doctastic_help_container').style.display != "none") {
        Element.update.delay(0.9, 'doctastic_help_showhide', Zikula.__('Help','module_doctastic'));
    } else {
        Element.update.delay(0.9, 'doctastic_help_showhide', Zikula.__('Hide Help','module_doctastic'));
    }
    switchdisplaystate('doctastic_help_container');
}
