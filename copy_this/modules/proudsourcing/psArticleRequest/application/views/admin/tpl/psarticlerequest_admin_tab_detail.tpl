[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="readonly" value=""}]
[{if $shopid != "oxbaseshop" && $shopid != "1" }]
    [{assign var="readonly" value="readonly=\"readonly\" disabled=\"disabled\""}]
[{/if}]

<script type="text/javascript">
<!--
function editThis( sID, sListType)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value=sListType+'_tab_detail';
    oTransfer.submit();

    if (parent.list != null)
    {
        var oSearch = parent.list.document.getElementById("search");
        oSearch.sort.value = '';
        oSearch.cl.value=sListType+'_list';
        oSearch.actedit.value=0;
        oSearch.oxid.value=sID;
        oSearch.submit();
    }
}
//-->
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="psarticlerequest_admin_tab_detail">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post" onSubmit="copyLongDesc( 'psarticlerequest__oxlongdesc' );">
       [{ $oViewConf->getHiddenSid() }]
       <input type="hidden" name="cl" value="psarticlerequest_admin_tab_detail">
       <input type="hidden" name="fnc" value="">
       <input type="hidden" name="oxid" value="[{ $oxid }]">
       <input type="hidden" name="editval[psarticlerequest__oxid]" value="[{ $oxid }]">
       <input type="hidden" name="editval[psarticlerequest__oxlongdesc]" value="">

       <table cellspacing="0" cellpadding="0" border="0" width="98%">
       [{if $edit}]
       [{assign var="oArticle" value=$edit->getArticle()}]
       <tr>
         <td valign="top" class="edittext" valign="top" style="padding-top:10px;padding-left:10px;">
           <table cellspacing="0" cellpadding="0" border="0" width="100%">
             [{block name="admin_ps_article_request_summary"}]
                 [{if $mail_succ}]
                 <tr><td class="edittext" height="17" colspan="2"><b>[{ oxmultilang ident="PS_ARTICLE_REQUEST_DETAIL_SUCCESS" }]</b></td></tr>
                 [{/if}]
                 [{if $mail_err}]
                 <tr><td class="edittext" height="17" colspan="2" style="color: #D81F01;"><b>[{ oxmultilang ident="PS_ARTICLE_REQUEST_DETAIL_ERROR" }]</b></td></tr>
                 [{/if}]
                 <tr><td class="edittext" height="17"><b>[{oxmultilang ident="PS_ARTICLEREQUEST_STOCK"}]:</b></td><td class="edittext">[{$oArticle->oxarticles__oxstock->value}]</td></tr>
                 <tr><td class="edittext" height="17"><b>[{ oxmultilang ident="PS_ARTICLEREQUEST_EMAIL" }]:</b></td><td class="edittext">[{$edit->psarticlerequest__oxemail->value}]</td></tr>
                 <tr><td class="edittext" height="17"><b>[{ oxmultilang ident="PS_ARTICLEREQUEST_NAME" }]:</b></td><td class="edittext" nowrap><a href="Javascript:editThis( '[{$edit->oUser->oxuser__oxid->value}]','user');" class="edittext">[{$edit->oUser->oxuser__oxlname->value}] [{$edit->oUser->oxuser__oxfname->value}]</a></td></tr>
                 <tr><td class="edittext" height="17" nowrap><b>[{ oxmultilang ident="PS_ARTICLEREQUEST_CONFIRMATION_DATE" }]:&nbsp;&nbsp;</b></td><td class="edittext" nowrap>[{$edit->psarticlerequest__oxinsert|oxformdate}]</td></tr>
                 <tr><td class="edittext" height="17"><b>[{ oxmultilang ident="PS_ARTICLEREQUEST_SHIPPING_DATE" }]:</b></td><td class="edittext">[{$edit->psarticlerequest__oxsended|oxformdate}]</td></tr>
                 <tr><td class="edittext" height="17"><b>[{ oxmultilang ident="PS_ARTICLEREQUEST_PRODUCT" }]:</b></td><td class="edittext">[{$edit->getTitle()}]</td></tr>
             [{/block}]
             <tr><td class="edittext" height="17"><br><br><br></td><td class="edittext">
               <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="PS_ARTICLE_REQUEST_DETAIL_EMAILSEND" }]" onClick="Javascript:document.myedit.fnc.value='send'" [{$readonly }]>
             </td></tr>
           </table>
         </td>
         <td>&nbsp;&nbsp;&nbsp;</td>
         [{block name="admin_ps_article_request_editor"}]
             <td valign="top" class="edittext" align="left">
                 [{ $editor }]
             </td>
         [{/block}]
       </tr>
       [{/if}]
     </table>
 </form>
[{include file="bottomnaviitem.tpl" }]

[{include file="bottomitem.tpl"}]
