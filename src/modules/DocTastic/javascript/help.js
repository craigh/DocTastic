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
    $('doctastic_help_collapse').addClassName('z-toggle-link');
    if ($('doctastic_help_container').style.display != "none") {
        $('doctastic_help_collapse').removeClassName('z-toggle-link-open');
        $('doctastic_help_showhide').update(Zikula.__('Show','module_doctastic'));
        $('doctastic_help_container').hide();
    }
}

function doctastic_help_click()
{
    if ($('doctastic_help_container').style.display != "none") {
        Element.removeClassName.delay(0.9, $('doctastic_help_collapse'), 'z-toggle-link-open');
        $('doctastic_help_showhide').update(Zikula.__('Show','module_doctastic'));
    } else {
        $('doctastic_help_collapse').addClassName('z-toggle-link-open');
        $('doctastic_help_showhide').update(Zikula.__('Hide','module_doctastic'));
    }
    switchdisplaystate('doctastic_help_container');
}
