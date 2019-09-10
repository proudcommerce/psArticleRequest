[{if $oViewConf->getTopActiveClassName() == 'details'}]
    [{if $oView->psArticleRequestSend()}]
        [{assign var="_statusMessage" value="PS_ARTICLEREQUEST_SUCCESS"|oxmultilangassign}]
        [{include file="message/success.tpl" statusMessage=$_statusMessage}]
    [{/if}]
[{/if}]

[{$smarty.block.parent}]