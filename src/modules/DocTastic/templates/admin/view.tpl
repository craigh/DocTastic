{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' set='icons/large' src='info.gif'}</div>
    <h2>{gt text="Location:"} {$directory}</h2>
    {$navigation}
    {if $document}
    <div>
        <div class="z-adminpageicon">{img modname='core' set='icons/large' src='filenew.gif'}</div>
        <h2>{gt text="Document Name:"} {$documentname}</h2>
        <div id='DocTastic_document'>
            {$document}
        </div>
    </div>
    {/if}
</div><!-- /z-admincontainer -->