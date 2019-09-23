[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var="readonly" value=""}]
[{if $shopid != "oxbaseshop" && $shopid != "1" }]
    [{assign var="readonly" value="readonly=\"readonly\" disabled=\"disabled\""}]
[{/if}]

<script type="text/javascript">

</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="psarticlerequest_admin_settings_tab_detail">
</form>

<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
       [{ $oViewConf->getHiddenSid() }]
       <input type="hidden" name="cl" value="psarticlerequest_admin_settings_tab_detail">
       <input type="hidden" name="fnc" value="">
       <input type="hidden" name="oxid" value="[{ $oxid }]">

       <table cellspacing="0" cellpadding="0" border="0" width="98%">
       <tr>
         <td valign="top" class="edittext" valign="top" style="padding-top:10px;padding-left:10px;">
           <table cellspacing="0" cellpadding="0" border="0" width="100%">
             [{block name="admin_ps_requetarticle_settings_main"}]
                 <tr>
                     <td class="edittext" height="17">
                         <b>[{oxmultilang ident="PS_ARTICLEREQUEST_LIMITTOCATEGORY" suffix="COLON" }]</b>
                     </td>
                     <td class="edittext">
                         <input [{$readonly}] type="button" value="[{oxmultilang ident="GENERAL_ASSIGNCATEGORIES"}]" class="edittext" onclick="JavaScript:showDialog('&cl=psarticlerequest_admin_settings_tab_detail&aoc=1&oxid=main');">
                     </td>
                 </tr>
             [{/block}]
             [{*<tr><td class="edittext" height="17"><br><br><br></td><td class="edittext">
               <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly }]>
             </td></tr>*}]
           </table>
         </td>
         <td>&nbsp;&nbsp;&nbsp;</td>
       </tr>
     </table>
 </form>
[{include file="bottomnaviitem.tpl" }]

[{include file="bottomitem.tpl"}]
