[{oxscript include="js/libs/jqBootstrapValidation.min.js" priority=10}]
[{oxscript add="$('input,select,textarea').not('[type=submit]').jqBootstrapValidation();"}]

<p>[{ oxmultilang ident="PS_ARTICLEREQUEST_WHEN_INFORM_TITLE" }]</p>

<form class="form-horizontal" name="articlerequest" action="[{$oViewConf->getSslSelfLink()}]" method="post" role="form" novalidate="novalidate">
    <div>
        [{ $oViewConf->getHiddenSid() }]
        [{ $oViewConf->getNavFormParams() }]
        <input type="hidden" name="cl" value="details">
        [{if $oDetailsProduct}]
            <input type="hidden" name="anid" value="[{$oDetailsProduct->oxarticles__oxid->value}]">
        [{/if}]
        <input type="hidden" name="fnc" value="request_product">
        [{assign var="oCaptcha" value=$oView->getCaptcha() }]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    </div>

    [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxfname}]

    <div class="form-group verify">
        <div class="col-lg-10 controls">
            <p>[{oxmultilang ident="PS_ARTICLEREQUEST_EMAIL"}]</p>
            <input id="contactEmail" type="email" name="pa[email]"  size=70 maxlength=40 value="[{ if $editval.oxuser__oxusername }][{$editval.oxuser__oxusername}][{else}][{ $oxcmp_user->oxuser__oxusername->value }][{/if}]" class="form-control" required="required">
        </div>
    </div>

    <div class="form-group verify">
        <div class="col-lg-10 controls">
            <p>[{oxmultilang ident="VERIFICATION_CODE"}]</p>
            [{assign var="oCaptcha" value=$oView->getCaptcha()}]
            <div class="input-group">
                [{if $oCaptcha->isImageVisible()}]
                <span class="input-group-addon">
                        <img src="[{$oCaptcha->getImageUrl()}]" alt="">
                    </span>
                [{else}]
                <span class="input-group-addon verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
                [{/if}]
                <input type="text" name="c_mac" value="" class="form-control" required="required">
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-10 controls">
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-envelope"></i> [{oxmultilang ident="SEND"}]
            </button>
        </div>
    </div>
</form>
