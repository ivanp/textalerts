<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Simplify your churches communications with us!  <i><?php // echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div style="width: 100%; text-align: center">


<div id="media">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="496" height="480" id="csSWF">
                <param name="movie" value="media/player.swf" />
                <param name="quality" value="best" />
                <param name="bgcolor" value="#1a1a1a" />
                <param name="allowfullscreen" value="true" />
                <param name="scale" value="showall" />
                <param name="allowscriptaccess" value="always" />
                <param name="flashvars" value="thumb=media/FirstFrame.png&containerwidth=496&containerheight=480&content=myalerts.mp4&autostart=false&blurover=false&autohide=true&smoothing=true&showbranding=false&showstartscreen=true&color=0x1A1A1A,0x1A1A1A" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="media/player.swf" width="496" height="480">
                    <param name="quality" value="best" />
                    <param name="bgcolor" value="#1a1a1a" />
                    <param name="allowfullscreen" value="true" />
                    <param name="scale" value="showall" />
                    <param name="allowscriptaccess" value="always" />
                    <param name="flashvars" value="thumb=media/FirstFrame.png&containerwidth=496&containerheight=480&content=myalerts.mp4&autostart=false&blurover=false&autohide=true&smoothing=true&showbranding=false&showstartscreen=true&color=0x1A1A1A,0x1A1A1A" />
                <!--<![endif]-->
                    <div id="noUpdate">
                        <p>The Camtasia video content presented here requires a more recent version of the Adobe Flash Player. If you are using a browser with JavaScript disabled please enable it now. Otherwise, please update your version of the free Flash Player by <a href="http://www.adobe.com/go/getflashplayer">downloading here</a>.</p>
                    </div>
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
        </div>
        <!-- Users looking for simple object / embed tags can copy and paste the needed tags below.
        <div id="media">
            <object id="csSWF" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="496" height="480" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0">
                <param name="src" value="media/player.swf"/>
                <param name="bgcolor" value="#1a1a1a"/>
                <param name="quality" value="best"/>
                <param name="allowScriptAccess" value="always"/>
                <param name="allowFullScreen" value="true"/>
                <param name="scale" value="showall"/>
                <param name="flashVars" value="thumb=media/FirstFrame.png&containerwidth=978&containerheight=947&content=myalerts.mp4&autostart=false&blurover=false&autohide=true&smoothing=true&showbranding=false&showstartscreen=true"/>
                <embed name="csSWF" src="media/player.swf" width="496" height="480" bgcolor="#1a1a1a" quality="best" allowScriptAccess="always" allowFullScreen="true" scale="showall" flashVars="thumb=media/FirstFrame.png&containerwidth=978&containerheight=947&content=myalerts.mp4&autostart=false&blurover=false&autohide=true&smoothing=true&showbranding=false&showstartscreen=true&color=0x1A1A1A,0x1A1A1A" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
            </object>
        </div>
        -->

<br/>
My church alerts is designed to help small and large churches communicate effectively with their members using text messages and email.  Church staff members can easily create groups (Women's Ministry, High School Parents, High School Students, Etc) and church members can easily subscribe to these groups.
</div>

<?php

//echo 'Guest='.var_export(Yii::app()->user->checkAccess('Guest'), true).'<br/>';
//echo 'Authenticated='.var_export(Yii::app()->user->checkAccess('Authenticated'), true).'<br/>';
////echo 'DoThis123='.var_export(Yii::app()->user->checkAccess('DoThis123'), true).'<br/>';
//
//$company = Company::model()->findByPk(1);
//$params = array('company' => $company);
////var_dump($params['company']->user_id);
//var_dump(Yii::app()->user->checkAccess('ManageOwnCompany', $params, false));
//var_dump(Yii::app()->user->checkAccess('ManageCompany', $params, false));
//var_dump(Yii::app()->user->checkAccess('SendMessageCompany', $params, false));
//
//$company = Company::model()->findByPk(1);
//foreach ($company->senders as $admin)
//{
//	echo 'Admin: '.$admin->email.'<br/>';
//}