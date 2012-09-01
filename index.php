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
        
        // This fetches some things that you like . 'limit=*" only returns * values.
        // To see the format of the data you are retrieving, use the "Graph API
        // Explorer" which is at https://developers.facebook.com/tools/explorer/
        $likes = idx($facebook->api('/me/likes?limit=4'), 'data', array());
        
        // This fetches 4 of your friends.
        $friends = idx($facebook->api('/me/friends?limit=4'), 'data', array());
        
        // And this returns 16 of your photos.
        $photos = idx($facebook->api('/me/photos?limit=16'), 'data', array());
        
        // Here is an example of a FQL call that fetches all of your friends that are
        // using this app
        $app_using_friends = $facebook->api(array(
                                                  'method' => 'fql.query',
                                                  'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
                                                  ));
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
        <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
        <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
        <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
        <meta property="og:description" content="Geomusic" />
        <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />
        
        <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
        <script src="http://www.parsecdn.com/js/parse-1.0.0.min.js"></script>
        <script type="text/javascript">
            //Initialization of PArse.com the database
            Parse.initialize("3oz87vs9DLzbaNPdd2Ih4YhJteoFaQGGNbC4i22D", "N1Us2fJby6wxLJT39kg1erQMnTP7QjZ7tO4p6bk2");
            
            //Global variables
            var nameSongSelected=''; 
            var hrefSong='';
            var lat='51.523777878854176'; //Location in case that the geolocations doesn't work
            var long='-0.04055500030517578';
            var placeId=''; //Data about the place selected
            var placeName='';
            var idSongObj='';
            
            var availablePlaces=new Array();
            
           //Fucntion to search the tracks in the Spotify library from a name introduced by the user.
            
            function searchSong(){ 
                var name= $("#songName").val();
               alert("name");
                var url="http://ws.spotify.com/search/1/track.json?q="+name;
                url=encodeURI(url);
                $.ajax({
                       url: url,
                       dataType: "json",
                       success: function(data, textStatus, jqXHR){
                      
                             fillResults(data);
                             },
                             error: function(jqXHR, textStatus, errorThrown){
                             alert('login error: ' + textStatus);
                             }
                             });
                       
                       }
                       
                     //Function that get the data from the Spotify search adn show the results in a list  
                function fillResults(data){
                       var track=data.tracks[0];
                       var i=0;
                    
                       while(((typeof(track)) != 'undefined') && (i<10)){
                    var pars="'"+track.name+"','"+track.href+"'";
                       $("#listResults").append('<li><a href="#location" onclick="saveSong('+pars+');"><h3>'+track.name+' ('+track.artists[0].name+') </h3></a></li>');
                           i++;
                       track=data.tracks[i];
                           
                       }
                    $("#listResults").listview('refresh');
                    

                       }
            
            
            //This function is called when the user select a song and store the data about that track, calling after the getPlaces() funciton.
            function saveSong(name, href){
                
                nameSongSelected=name;
                hrefSong=href;
                getLocations();
            }
            
           
        //Geolocation function to get the actual location of the user
           function getLocations()
{
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(data) {
                                                  localStorage.lat = data['coords']['latitude'];
                                                 localStorage.lng = data['coords']['longitude'];
                                                 alert("Lat"+localStorage.lat+"Long"+localStorage.lng);
                                                 getPlaces();}
                                                 );
      
    } else {
        error('Geolocation is not supported.');
    }
}

            
       
                        
           
            //This function search the near Facebook Places in relation with the location of the user and show the results in a list
            
            function getPlaces(){
                
                
                
                FB.api('search?center='+localStorage.lat+','+localStorage.lng, { limit: 10, type: 'place', distance : 1000 }, function(response) {
                      
                       var place=response.data[0];
                       var i=0;
                       
                       while(((typeof(place)) != 'undefined') && (i<10)){
                       availablePlaces[i]=place;
               
                       $("#listResultsLocations").append('<li><a href="#presend" onclick="savePlace('+i+');"><h3>'+place.name+' </h3></a></li>');
                       i++;
                       place=response.data[i];
                       
                       }
                       $("#listResultsLocations").listview('refresh');

                       });                
            }
            
            //This function save the info about the place selected by the user and call the function to show the values
            
            function savePlace(p){
            
                placeId=availablePlaces[p].id;
                placeName=availablePlaces[p].name;
                lat=availablePlaces[p].location.latitude;
                long=availablePlaces[p].location.longitude;
                setValuestoShow();
                
            }
            
            function setValuestoShow(){
                alert(nameSongSelected+placeName);
                $("#songNameShow").append(""+nameSongSelected);
                $("#placeShow").append(""+placeName);
                $("#titleSong").val(nameSongSelected);
                
                $("#idPlace").val(placeId);
                $("#urlSong").val(hrefSong);
            }
            
            function sendAction(){
                saveTrack();
                
            }
             //This function save the musicIn object in the database.
            function saveTrack(){
                var Track = Parse.Object.extend("Track");
                var track = new Track();
                track.save({title: nameSongSelected,
                           url: hrefSong,
                           idPlace: placeId,
                           namePlace: placeName}, {
                           success: function(object,result) {
                           
                           $("#idSongH").val(result.objectId);
                           idSongObj=result.objectId;
                           alert("yay! You are dancing in the DB!!!");
                          
                           return true;
                           }
                           });
            }
            
          
            
            function fb(){alert("whatever");}
                       
                       
                       </script>
        
        <!--[if IE]>
         <script type="text/javascript">
         var tags = ['header', 'section'];
         while(tags.length)
         document.createElement(tags.pop());
         </script>
         <![endif]-->
    </head>
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
        
        
        
        
        <div data-role="page" id="welcome" data-theme="a">
            
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            
            <div data-role="content">
                <?php if (isset($basic)) { ?>
                <p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></p>
                <h1>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong></h1>
                <p><a href="#spotify" data-role="button">Start Dancing!</a></p>
            </div>
            <?php } else { ?>
            <div>
                <h1>Welcome to GeoMusic! Log in with Facebook please!</h1>
                <div class="fb-login-button" data-scope="user_likes,user_photos"></div>
            </div>
            <?php } ?>
        </div>
        </div>
        
        <div data-role="page" id="spotify">
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            <div data-role="content">
                <fieldset>
                    <div data-role="fieldcontain">
                        <h1><label for="songName">What song are you dancing?:</label></h1>
                        <input type="text" name="song" id="songName" value=""  />
                    </div> <a href="#spotifyresults" data-role="button" onclick="searchSong();" >Search Song!</a>
               
                </fieldset>
                
            </div></div>
        
        <div data-role="page" id="spotifyresults">
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            <div data-role="content">
                
                
                <div class="content-primary">	
                    <ul data-role="listview" id="listResults">
                    </ul>
                </div><!--/content-primary -->	
            </div>
        </div>
        
        <div data-role="page" id="location">
            <div data-role="header" data-theme="a">
<h1 data-theme="a">GeoMusic: Where are ypu dancing?</h1>
            </div><!-- /header -->
            <div data-role="content">
                
                
                <div class="content-primary">
                    <h3> Where are you dancing?</h3>
                    <ul data-role="listview" id="listResultsLocations">
                    </ul>
                </div><!--/content-primary -->	
            </div>
        </div>
        
        <div data-role="page" id="presend">
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            <div data-role="content">
                <form method="post" action="track.php" id="formSend" onsubmit="return saveTrack();" data-ajax="false">
                <h2> So....you are dancing:</h2>
                <fieldset>
                    <div data-role="fieldcontain">
                        <h3>The song:</h3>
                        <p name="songShow" id="songNameShow"  ></p>
                    </div> 
                    <div data-role="fieldcontain">
                        <h3>At:</h3>
                        <p  name="placeShow" id="placeShow"  ></p>
                    </div>
                    
                </fieldset>
                <input type="hidden" name="idSong" value="" id="idSongH"/>
                    <input type="hidden" name="title" value="" id="titleSong" />
                    <input type="hidden" name="image" value="" />
                    <input type="hidden" name="description" value="danced track"  />
                    <input type="hidden" name="placeId" value="" id="idPlace"/>
                    <input type="hidden" name="urlSong" value="" id="urlSong" />
                  <button type="submit" value="dance" name="dance" data-role="button" >Dance!</a>
            </form>
                </div>
            </div>
        </div>

    </body>
</html>
