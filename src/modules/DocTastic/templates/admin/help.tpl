{ajaxheader modname='DocTastic' filename='help.js'}
{pageaddvar name='stylesheet' value='modules/DocTastic/style/helpstyle.css'}
<div id='doctastic_help_container'>
    <a id="doctastic_help_closer" class='doctastic_helplink' href="javascript:void(0);" onclick='doctastic_help_click();'>
	<span class='z-icon-es-close'>{gt text='Close' domain='module_doctastic'}</span>
    </a>
    <a class='doctastic_helplink' href="{modurl modname='DocTastic' type='user' func='view' docmodule=$doctastic_help.topmodule}">
	<span class='z-icon-es-info'>{gt text='Full Documentation' domain='module_doctastic'}</span>
    </a>
    <h2>{$doctastic_help.topmodule}/{$doctastic_help.lang}/{$doctastic_help.type}/{$doctastic_help.func} {gt text='inline help' domain='module_doctastic'}</h2>
    {pageaddvar name='stylesheet' value='modules/DocTastic/style/markdown.css'}
    <div id="doctastic_document">{$doctastic_help.html}</div>
</div>
<div id='doctastic_linkrow'><span class='sub'>
    <a id="doctastic_help_collapse" href="javascript:void(0);">
	<span class='z-icon-es-help' id="doctastic_help_showhide">{gt text='Help' domain='module_doctastic'}</span>
    </a>
</span></div>