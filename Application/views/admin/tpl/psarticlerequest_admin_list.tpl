[{include file="headitem.tpl"
title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}] [{assign
var="where" value=$oView->getListFilter()}] [{ if $shopid !=
"oxbaseshop" }] [{assign var="readonly" value="readonly disabled"}]
[{else}] [{assign var="readonly" value=""}] [{/if}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
}
//-->
</script>

<div id="liste">

	<form name="search" id="search"
		action="[{ $oViewConf->getSelfLink() }]" method="post">
		[{include file="_formparams.tpl" cl="psarticlerequest_admin_list"
		lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang
		editlanguage=$actlang}]

		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<colgroup>
				[{block name="admin_articlerequest_colgroup"}]
				<col width="15%">
				<col width="15%">
				<col width="10%">
				<col width="10%">
				<col width="20%">
				<col width="10%">
				[{/block}]
			</colgroup>
			<tr class="listitem">
				[{block name="admin_articlerequest_list_filter"}]
				<td valign="top" class="listfilter first" height="20">
					<div class="r1">
						<div class="b1">
							<input class="listedit" type="text" size="20" maxlength="128"
								name="where[psarticlerequest][oxemail]"
								value="[{ $where.psarticlerequest.oxemail }]">
						</div>
					</div>
				</td>
				<td valign="top" class="listfilter" height="20">
					<div class="r1">
						<div class="b1">
							<input class="listedit" type="text" size="20" maxlength="128"
								name="where[oxuser][oxlname]"
								value="[{ $where.oxuser.oxlname }]">
						</div>
					</div>
				</td>
				<td valign="top" class="listfilter" height="20">
					<div class="r1">
						<div class="b1">
							<input class="listedit" type="text" size="20" maxlength="128"
								name="where[psarticlerequest][oxinsert]"
								value="[{ $where.psarticlerequest.oxinsert }]">
						</div>
					</div>
				</td>
				<td valign="top" class="listfilter" height="20">
					<div class="r1">
						<div class="b1">
							<input class="listedit" type="text" size="20" maxlength="128"
								name="where[psarticlerequest][oxsended]"
								value="[{ $where.psarticlerequest.oxsended }]">
						</div>
					</div>
				</td>
				<td valign="top" class="listfilter" height="20">
					<div class="r1">
						<div class="b1">
							<input class="listedit" type="text" size="20" maxlength="128"
								name="where[oxarticles][oxtitle]"
								value="[{ $where.oxarticles.oxtitle }]">
						</div>
					</div>
				</td>
				<td valign="top" class="listfilter" height="20">
					<div class="r1">
						<div class="b1"><input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]" onClick="Javascript:document.search.lstrt.value=0;"></div>
					</div>
				</td> [{/block}]
			</tr>
			<tr>
				[{block name="admin_articlerequest_list_sorting"}]
				<td class="listheader first" height="15">&nbsp;<a
					href="Javascript:top.oxid.admin.setSorting( document.search, 'psarticlerequest', 'oxemail', 'asc');document.search.submit();"
					class="listheader">[{ oxmultilang ident="PS_ARTICLEREQUEST_EMAIL" }]</a></td>
				<td class="listheader" height="15"><a
					href="Javascript:top.oxid.admin.setSorting( document.search, 'oxuser', 'oxlname', 'asc');top.oxid.admin.setSorting( document.search, 'oxuser', 'oxfname', 'asc');document.search.submit();"
					class="listheader">[{ oxmultilang ident="PS_ARTICLEREQUEST_NAME" }]</a></td>
				<td class="listheader" height="15"><a
					href="Javascript:top.oxid.admin.setSorting( document.search, 'psarticlerequest', 'oxinsert', 'asc');document.search.submit();"
					class="listheader">[{ oxmultilang
						ident="PS_ARTICLEREQUEST_CONFIRMATION_DATE" }]</a></td>
				<td class="listheader" height="15"><a
					href="Javascript:top.oxid.admin.setSorting( document.search, 'psarticlerequest', 'oxsended', 'asc');document.search.submit();"
					class="listheader">[{ oxmultilang
						ident="PS_ARTICLEREQUEST_SHIPPING_DATE" }]</a></td>
				<td class="listheader" height="15"><a
					href="Javascript:top.oxid.admin.setSorting( document.search, 'oxarticles', 'oxtitle', 'asc');document.search.submit();"
					class="listheader">[{ oxmultilang ident="GENERAL_ITEM" }]</a></td>
				<td class="listheader" height="15">&nbsp;</td> [{/block}]
			</tr>

			[{assign var="blWhite" value=""}] [{assign var="_cnt" value=0}]
			[{foreach from=$mylist item=listitem}] [{assign var="_cnt"
			value=$_cnt+1}]
			<tr id="row.[{$_cnt}]">
				[{block name="admin_articlerequest_list_item"}] [{ if
				$listitem->blacklist == 1}] [{assign var="listclass" value=listitem3
				}] [{ else}] [{assign var="listclass" value=listitem$blWhite }] [{
				/if}] [{ if $listitem->getId() == $oxid }] [{assign var="listclass"
				value=listitem4 }] [{ /if}]
				<td valign="top" class="[{$listclass}]" height="15">
					<div class="listitemfloating">
						<a
							href="Javascript:top.oxid.admin.editThis('[{ $listitem->psarticlerequest__oxid->value}]');"
							class="[{if $listitem->getRequestStatus()==1}]listitemred[{elseif $listitem->getRequestStatus()==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{
							$listitem->psarticlerequest__oxemail->value }]</a>
					</div>
				</td>
				<td valign="top" class="[{$listclass}]" height="15"><div
						class="listitemfloating">
						<a
							href="Javascript:top.oxid.admin.editThis('[{ $listitem->psarticlerequest__oxid->value}]');"
							class="[{if $listitem->getRequestStatus()==1}]listitemred[{elseif $listitem->getRequestStatus()==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{
							$listitem->psarticlerequest__userlname->value }] [{
							$listitem->psarticlerequest__userfname->value }]</a>
					</div></td>
				<td valign="top" class="[{$listclass}]" height="15"><div
						class="listitemfloating">
						<a
							href="Javascript:top.oxid.admin.editThis('[{ $listitem->psarticlerequest__oxid->value}]');"
							class="[{if $listitem->getRequestStatus()==1}]listitemred[{elseif $listitem->getRequestStatus()==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{
							$listitem->psarticlerequest__oxinsert|oxformdate }]</a>
					</div></td>
				<td valign="top" class="[{$listclass}]" height="15"><div
						class="listitemfloating">
						<a
							href="Javascript:top.oxid.admin.editThis('[{ $listitem->psarticlerequest__oxid->value}]');"
							class="[{if $listitem->getRequestStatus()==1}]listitemred[{elseif $listitem->getRequestStatus()==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{
							$listitem->psarticlerequest__oxsended|oxformdate }]</a>
					</div></td>
				<td valign="top" class="[{$listclass}]" height="15"><div
						class="listitemfloating">
						<a
							href="Javascript:top.oxid.admin.editThis('[{ $listitem->psarticlerequest__oxid->value}]');"
							class="[{if $listitem->getRequestStatus()==1}]listitemred[{elseif $listitem->getRequestStatus()==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{
							$listitem->getTitle() }]</a>
					</div></td>
				<td class="[{$listclass}]">[{ if !$listitem->isOx() }] <a
					href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->psarticlerequest__oxid->value }]');"
					class="delete" id="del.[{$_cnt}]" [{include file="help.tpl"
					helpid=item_delete}]></a> [{/if}]
                    [{/block}]
				</td>
			</tr>
			[{if $blWhite == "2"}] [{assign var="blWhite" value=""}] [{else}]
			[{assign var="blWhite" value="2"}] [{/if}] [{/foreach}] [{include
			file="pagenavisnippet.tpl" colspan="6"}]
		</table>
	</form>
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
