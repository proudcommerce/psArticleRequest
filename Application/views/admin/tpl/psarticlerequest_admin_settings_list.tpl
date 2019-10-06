[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

[{if $shopid != "oxbaseshop" }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<div id="liste">

</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="PS_ARTICLE_REQUEST_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="PS_ARTICLE_REQUEST_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
