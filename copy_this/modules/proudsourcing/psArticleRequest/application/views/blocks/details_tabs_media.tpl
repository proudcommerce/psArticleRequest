[{$smarty.block.parent}]
[{block name="details_tabs_psarticlequest"}]
    [{if $oDetailsProduct->getStockStatus() == -1}]
        [{capture append="tabs"}]<a href="#ps_article_request" data-toggle="tab">[{oxmultilang ident="PS_ARTICLEREQUEST_TAB_LINK_TITLE"}]</a>[{/capture}]
        [{capture append="tabsContent"}]
            <div id="ps_article_request" class="tab-pane[{if $blFirstTab}] active[{/if}]">
                [{include file="form/ps_article_request_form.tpl"}]
            </div>
            [{assign var="blFirstTab" value=false}]
        [{/capture}]
    [{/if}]
[{/block}]