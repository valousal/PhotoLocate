<!DOCTYPE html>
<html>
    <head>
    	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" /> 
        <title>PhotoLocate</title>
        
        <!-- bootstrap -->
        <link rel="stylesheet" type="text/css" href="media/bootstrap/stylesheets/styles.css" />
    


        <!-- references -->
        <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

        <!-- angularjs -->
        <script src="js/lib/angular.min.js"></script>
        <script src="js/lib/angular-route.min.js"></script>
        <script src="js/lib/angular-resource.min.js"></script>
        <script src="js/lib/angular-sanitize.min.js"></script>
        <script src="js/lib/angular-timer.min.js"></script>

        <!-- custom scripts avec les controlleurs -->
        <script src="js/dist/app.js"></script>
        
        <script src="js/dist/controllers/GameController.js"></script>
        <script src="js/dist/services/Games.js"></script>
        <script src="js/dist/services/Session.js"></script>
    
        <!-- Leaflet -->
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
        <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
        

        <style>
            #map { height: 180px; }
        </style>
    </head>
   	

   	<!-- BODY -->
    <body ng-app='Application'>

        <div class="row page-header">
            <h1 id='titre'><a href='#/'>PhotoLocate </a><small>Un Jeu qu'il est bien</small></h1>
        </div>

		<div ng-view>
		</div>


    </body>


</html>