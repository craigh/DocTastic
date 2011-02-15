{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{icon type="info" size="large"}</div>
<h2>{gt text="DocTastic settings"}&nbsp;({gt text="version"}&nbsp;{$modinfo.version})</h2>
<form class="z-form" action="{modurl modname="DocTastic" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
	<input type="hidden" name="authid" value="{insert name="generateauthkey" module="DocTastic"}" />
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
			<label for="navType">{gt text='Navigation type'}</label>
            {$navTypeSelector}
        </div>
		<div class="z-formrow">
			<label for="addCore">{gt text="Add the Core's /docs directory to the Navigation Tree"}</label>
			<input type="checkbox" value="1" id="addCore" name="addCore"{if $modvars.DocTastic.addCore eq true} checked="checked"{/if}/>
		</div>
		<div class="z-formrow">
			<label for="enableLanguages">{gt text='Enable language filter'}</label>
			<input type="checkbox" value="1" id="enableLanguages" name="enableLanguages"{if $modvars.DocTastic.enableLanguages eq true} checked="checked"{/if}/>
		</div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" class='z-btgreen' __alt="Save" __title="Save" __text="Save"}
        <a class='z-btred' href="{modurl modname="DocTastic" type="admin"}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
</div><!-- /z-admincontainer -->