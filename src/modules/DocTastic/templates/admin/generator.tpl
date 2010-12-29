{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='modules/DocTastic/javascript/markitup/jquery.markitup.js'}
{pageaddvar name='javascript' value='modules/DocTastic/javascript/markitupsettings.js'}
{pageaddvar name='stylesheet' value='modules/DocTastic/javascript/markitup/skins/markitup/style.css'}
{pageaddvar name='stylesheet' value='modules/DocTastic/javascript/markitup/sets/markdown/style.css'}

{include file="admin/menu.tpl"}
<div class="z-admincontainer">
<div class="z-adminpageicon">{img modname='core' set='icons/large' src='filenew.gif'}</div>
<h2>{gt text="DocTastic Document Generator"}&nbsp;({gt text="version"}&nbsp;{$modinfo.version})</h2>
<div class="z-informationmsg">
        {gt text="This editor <strong>does not save</strong> your generated content anywhere. You have to cut and past the raw text to your own text file and save to your /docs directory. This is merely provided as a markdown viewer for ease in doc creation."}
    </div>
<textarea cols="80" rows="40" id="markItUp"></textarea>
<script type="text/javascript" >
   jQuery(document).ready(function() {
      jQuery("#markItUp").markItUp(mySettings);
   });
</script>
</div>