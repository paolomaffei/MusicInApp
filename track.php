<?php
    
    /**
     * This sample app is provided to kickstart your experience using Facebook's
     * resources for developers.  This sample app provides examples of several
     * key concepts, including authentication, the Graph API, and FQL (Facebook
     * Query Language). Please visit the docs at 'developers.facebook.com/docs'
     * to learn more about the resources available to you
     */
    
    // Provides access to app specific values such as your app id and app secret.
    // Defined in 'AppInfo.php'
    require_once('AppInfo.php');
    
    
    
    // This provides access to helper functions defined in 'utils.php'
    require_once('utils.php');
    
    
    /*****************************************************************************
     *
     * The content below provides examples of how to fetch Facebook data using the
     * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
     * do so.  You should change this section so that it prepares all of the
     * information that you want to display to the user.
     *
     ****************************************************************************/
    
    require_once('sdk/src/facebook.php');
    
    
    $facebook = new Facebook(array(
                                   'appId'  => AppInfo::appID(),
                                   'secret' => AppInfo::appSecret(),
                                   ));
    
    $user_id = $facebook->getUser();
    if ($user_id) {
        try {
            // Fetch the viewer's basic information
            $basic = $facebook->api('/me');
        } catch (FacebookApiException $e) {
            // If the call fails we check if we still have a user. The user will be
            // cleared if the error is because of an invalid accesstoken
            if (!$facebook->getUser()) {
                header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
                exit();
            }
        }
        
        
    }
    
    // Fetch the basic info of the app that they are using
    $app_info = $facebook->api('/'. AppInfo::appID());
    
    $app_name = idx($app_info, 'name', '');
    
    ?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />
        
        <title><?php echo he($app_name); ?></title>
        <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
        <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />
        
        <!--[if IEMobile]>
         <link rel="stylesheet" href="mobile.css" media="screen" type="text/css"  />
         <![endif]-->
        
        <!-- These are Open Graph tags.  They add meta data to your  -->
        <!-- site that facebook uses when your content is shared     -->
        <!-- over facebook.  You should fill these tags in with      -->
        <!-- your data.  To learn more about Open Graph, visit       -->
        <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
        <meta property="og:title" content="<?php echo he($app_name); ?>" />
        <meta property="og:type" content="geomusic:track" />
           <meta property="og:url" content="http://geomusic.herokuapp.com/api.php/track/?id= <?php echo $_POST['idSong']; ?> "/>
        <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
        <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
        <meta property="og:description" content="Geomusic" />
        <meta property="og:title" content=""/>
        <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />
        
        <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
       
             <script type="text/javascript">
$(document).ready(function() {
                  
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

                  alert($("#idDance"));
                  
$("#idDance").click(function(){
    console.log("hello");
    var id = getUrlVars()["idSong"];
    var placeSend=getUrlVars()["placeId"];
    var trUrl= "http://geomusic.herokuapp.com/api.php/track/?id="+id;
    FB.api('/me/geomusicapp:dance', 'post', 
           { track : trUrl,
           place: placeSend,
           access_token: 'AAAGXTZCI2OQ4BAOmYoTvqdrjnxnXDGF8KfLOtzSNitO1LMARqpZCBQlPWeJBZAgFZA05HwaxLGVaCuxBLZAgGfJ1cDjiwi0tSwZAZCZB9dYsyGsfA5HjuhMI'},function(response) {
           if (!response || response.error) {
           alert('Error occured');
           } else {
           alert('Post ID: ' + response.id);
           }});
    return true;
                    });
         
                  });

</script>    </head>
    <body>
        <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                        appId      : '<?php echo AppInfo::appID(); ?>', // App ID
                        channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
                        status     : true, // check login status
                        cookie     : true, // enable cookies to allow the server to access the session
                        xfbml      : true // parse XFBML
                        });
                
                // Listen to the auth.login which will be called when the user logs in
                // using the Login button
                FB.Event.subscribe('auth.login', function(response) {
                                   // We want to reload the page now so PHP can read the cookie that the
                                   // Javascript SDK sat. But we don't want to use
                                   // window.location.reload() because if this is in a canvas there was a
                                   // post made to this page and a reload will trigger a message to the
                                   // user asking if they want to send data again.
                                   window.location = window.location;
                                   });
                
                FB.Canvas.setAutoGrow();
            };
            
            // Load the SDK Asynchronously
            (function(d, s, id) {
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) return;
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/en_US/all.js";
             fjs.parentNode.insertBefore(js, fjs);
             }(document, 'script', 'facebook-jssdk'));
            
            
                       

            </script>
        
        
        
        
        
        
        <div data-role="page" id="presend">
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            <div data-role="content">
                <form method="post" action="track.php" id="formSend">
                    <h2> So....you are dancing:</h2>
                    <fieldset>
                        <div data-role="fieldcontain">
                            <h3>The song:</h3>
                            <p name="songShow" id="songNameShow"  ><?php echo  $_POST['title']; ?></p>
                        </div> 
                        <div data-role="fieldcontain">
                            <h3>Facebook Place Id:</h3>
                            <p  name="placeShow" id="placeShow"  ><?php echo  $_POST['placeId']; ?></p>
                        </div>
                        
                    </fieldset>
                
                    <a href="#"  data-role="button" id="idDance">Share on Facebook!</a>



                </form>
            </div>
        </div>
        </div>
        
    </body>
</html>

