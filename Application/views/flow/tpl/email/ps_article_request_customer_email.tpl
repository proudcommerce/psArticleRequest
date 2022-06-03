[{assign var="shop"      value=$oEmailView->getShop()}]
[{assign var="oViewConf" value=$oEmailView->getViewConfig()}]
[{assign var="user"      value=$oEmailView->getUser()}]


[{include file="email/html/header.tpl" title="PS_ARTICLE_REQUEST_MAIL_AV1"|oxmultilangassign}]

    [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV1" }] <b>[{$product->oxarticles__oxtitle->value}]</b> [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV2" }]
    <br><br>

    <a href="[{ $product->getLink() }]">[{ $product->getLink() }]</a>
    <br><br>

    [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV3" }] [{ $oArticleRequest->psarticlerequest__oxtimestamp->value|date_format:"%d.%m %Y" }] [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV4" }]
    <br><br>

[{include file="email/html/footer.tpl"}]
