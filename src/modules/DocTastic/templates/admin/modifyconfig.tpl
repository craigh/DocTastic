{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{img modname='core' set='icons/large' src='info.gif'}</div>
<h2>{gt text="DocTastic settings"}&nbsp;({gt text="version"}&nbsp;{$version})</h2>
<form class="z-form" action="{modurl modname="DocTastic" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
	<input type="hidden" name="authid" value="{insert name="generateauthkey" module="DocTastic"}" />
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
			<label for="navType">{gt text='Navigation type'}</label>
            {$navTypeSelector}
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.gif" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="DocTastic" type="admin"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
</div><!-- /z-admincontainer -->