<html >
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />
        
        <title></title>
<script type="text/javascript">

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

document.getElementById("idDance").addEventListener('click', function() 
                                                    {
                                                    console.log("hello");
                                                    var id = getUrlVars()["idSong"];
                                                    var placeSend=getUrlVars()["placeId"];
                                                    var trUrl= "http://geomusic.herokuapp.com/api.php/track/?id="+id;
                                                    FB.api('/me/geomusicapp:dance', 'post', 
                                                           { track : trUrl,
                                                           place: placeSend},function(response) {
                                                           if (!response || response.error) {
                                                           alert('Error occured');
                                                           } else {
                                                           alert('Post ID: ' + response.id);
                                                           }});
                                                    return true;
                                                    });



</script>
     
   
    </head>
    <body>
        <div id="fb-root"></div>
                
        
        
        
        
        
        <div data-role="page" id="presend">
            <div data-role="header" data-theme="a">
                <h1 data-theme="a">GeoMusic</h1>
            </div><!-- /header -->
            <div data-role="content">
              
                   HELLO
                
                    <a href="#"   id="idDance">Dance!</a>
           
        </div>
        </div>
        
    </body>
</html>

