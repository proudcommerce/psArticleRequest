[{$smarty.block.parent}]
[{block name="details_tabs_psarticlequest"}]
    [{assign var=variants value=$oDetailsProduct->getFullVariants(false)}]
    [{if $variants|@count}]
        [{foreach from=$variants item=variant}]
            [{if $variant->getStockStatus() == -1}]
                [{assign var=showArticleRequest value=true}]
            [{/if}]
        [{/foreach}]
    [{else}]
        [{if $oDetailsProduct->getStockStatus() == -1}]
            [{assign var=showArticleRequest value=true}]
        [{/if}]
    [{/if}]
    [{if $showArticleRequest && $oView->showPsArticleRequest()}]
        [{capture append="tabs"}]<a href="#ps_article_request" class="nav-link" data-toggle="tab">[{oxmultilang ident="PS_ARTICLEREQUEST_TAB_LINK_TITLE"}]</a>[{/capture}]
        [{capture append="tabsContent"}]
            <div id="ps_article_request" class="tab-pane[{if $blFirstTab}] active[{/if}]">
                [{include file="form/ps_article_request_form.tpl"}]
            </div>
            [{assign var="blFirstTab" value=false}]
        [{/capture}]
    [{/if}]
[{/block}]
