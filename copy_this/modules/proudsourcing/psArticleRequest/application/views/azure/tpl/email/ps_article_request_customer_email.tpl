[{ assign var="shop"      value=$oEmailView->getShop() }]
[{ assign var="oViewConf" value=$oEmailView->getViewConfig() }]

<!DOCTYPE HTML>
<html>
<head>
    <title>[{$title}]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$oEmailView->getCharset()}]">
</head>

<body bgcolor="#ffffff" link="#355222" alink="#18778E" vlink="#389CB4" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">

<div width="600" style="width: 600px">

    <div style="padding: 10px 0;">
        <img src="[{$oViewConf->getImageUrl('logo_email.png', false)}]" border="0" hspace="0" vspace="0" alt="[{ $shop->oxshops__oxname->value }]" align="texttop">
    </div>

    [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV1" }] <b>[{$product->oxarticles__oxtitle->value}]</b> [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV2" }]
    <br><br>

    <a href="[{ $product->getLink() }]">[{ $product->getLink() }]</a>
    <br><br>

    [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV3" }] [{ $oArticleRequest->psarticlerequest__oxtimestamp->value|oxformdate:'datetime':true }] [{ oxmultilang ident="PS_ARTICLE_REQUEST_MAIL_AV4" }]
    <br><br>

    <div style="border: 1px solid #3799B1; margin: 30px 0 15px 0; padding: 12px 20px; background-color: #eee; border-radius: 4px 4px 4px 4px; linear-gradient(center top , #FFFFFF, #D1D8DB) repeat scroll 0 0 transparent;">
        <p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 0; padding: 0;">
            [{ oxcontent ident="oxemailfooter" }]
        </p>
    </div>

</div>

</body>
</html>