app = angular.module('Application')
app.controller 'GameController', ($scope,$rootScope, $resource,$http,$location,$routeParams,$q,Session) ->


	## ---------------------------- Initialise le jeu et renvoi token and id
	## ---------------------------- Initialise le jeu et renvoi token and id
	## ---------------------------- Initialise le jeu et renvoi token and id
	$scope.createGame = ->
		$http({
			method: 'POST',
			url: '/PhotoLocate/service/play/games',
			data: {player: $scope.games.player, level: $scope.games.level, ville: $scope.games.ville},
			# headers: {'Content-Type': 'application/json'}
		}).success (games) ->
			Session.set('token', games.token)
			Session.set('id', games.id)
			# Session.set('player', games.player)
			$location.path("/games/") 
		.error -> 
			console.log('error createGame')
					
	## ---------------------------- Get les pictures
	## ---------------------------- Get les pictures
	## ---------------------------- Get les pictures
	$scope.GetPictures = ->

		$http({
			method: 'GET',
			url: '/PhotoLocate/service/play/games/'+Session.get('id')+'/photos?apiKey='+Session.get('token'),
			# data: {id: 7330},
			# headers: {'Content-Type': 'application/json'}
		}).success (pictures) ->
			console.log('Ok GetPicture')
			
		.error -> 
			console.log('error GetPicture')




	## ---------------------------- Function du Déroulement du jeu 
	## ---------------------------- Function du Déroulement du jeu
	## ---------------------------- Function du Déroulement du jeu
	$scope.InitGame = ->
		$http({
			method: 'GET',
			url: "/PhotoLocate/service/play/games/"+Session.get('id')+"?apiKey="+Session.get('token'),
			# data: {id: 7330},
			# headers: {'Content-Type': 'application/json'}

		}).success (game) ->
			# $scope.games = games;
			Session.set('lat', game.ville.lat)
			Session.set('lng', game.ville.lng)
			Session.set('zoom', game.zoom)
			Session.set('difficulte', game.difficulte)
			# Partie:
			$scope.scoreTotal = 0;
			$scope.scoreManche = 0;
			$scope.nbMancheMax = Session.get('difficulte').nb_photos
			$scope.nbManche = 0;
			$scope.timerValue = 0;
			$scope.zoom = Session.get('difficulte').temps
			$scope.distanceDiff =  Session.get('difficulte').distance
			# Map
			$scope.map = L.map('map').setView([Session.get('lat'), Session.get('lng')], $scope.zoom); 
			$scope.markerTry = L.marker();
			$scope.distance = 0;
			# $scope.origin = [10, 10]; # $scope.games.lat, $scope.games.lng

			$scope.greenIcon = L.icon({
				iconUrl: 'media/Image/marker-icon-red.png',
				iconSize:     [25, 41],  
				iconAnchor:   [12, 41], 
				shadowAnchor: [12, 41],  
				popupAnchor:  [0, -25] 
			});


			$scope.tile_layer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png',{
			}).addTo($scope.map);


			# -----------Fonction load layout a recopier:-------------------
			$scope.tile_layer.on 'load', ->
				$('#partieDebut').show();
				return



			## -------------------------- Affichage des pictures 
			return $scope.GetPictures().then (response) -> 
				Session.set('pictures', response.data)
				$scope.pictures = response.data

			## ----------------------------Déroulement du jeu

				$scope.map.on 'click', (obj) ->
					if $scope.timerRunning && $scope.markerTry 
						$scope.finManche(obj)
						return

				$scope.debutPartie = ->
					$('#partieDebut').remove();
					$('#scoreManche').hide();
					$('#partieDebut').hide();
					## ----------------------------recuperation de la 1er image
					
					arrayPhoto = new Array
					i = 0
					for object in Session.get('pictures')
						arrayPhoto[i] = { 'href' : object.href, 'lat' : object.image.lat, 'lng' : object.image.lng }
						i = i+1

					Session.set('arrayPhoto',arrayPhoto)
					$scope.photoone = arrayPhoto[0].href
					$scope.lat = arrayPhoto[0].lat
					$scope.lng = arrayPhoto[0].lng
					$scope.origin = [$scope.lat, $scope.lng]
					###(arrayPhoto) ->
						console.log(arrayPhoto[i]) ###
						# console.log(object.href)

					# arrayPhoto = new Array
					# for k,v of Session.get('pictures')
  						# console.log k + " is " + v
  						# for o of v
  							# console.log v.href
  							# arrayPhoto[o] = v.href
  							# console.log(arrayPhoto)
  					

					$('#mancheSuivante').show() 
					return

				$scope.mancheSuivante = ->
					$scope.scoreManche = 0;
					$scope.resetTimer();
					$scope.verifNbManche();
					return

				$scope.go = ->
					$scope.nbManche += 1;
					$('#mancheSuivante').hide();
					$('#shadow').hide();
					$scope.markerTry = L.marker();
					$scope.distance = 0;
					$scope.startTimer();
					return

				$scope.finManche = (obj) ->
					$scope.stopTimer();
					$scope.afficheScore(obj);
					$scope.showOrigin();
					setTimeout ->
						$scope.resetMap();				
						$('#shadow').show();
						$('#scoreManche').show();
						return
					,3000
					return

				$scope.noTime = ->
					$scope.stopTimer();
					$scope.showOrigin();
					setTimeout ->
						$scope.resetMap();				
						$('#shadow').show();
						$('#scoreManche').show();
						return
					,3000
					return


				$scope.showOrigin = ->
					setTimeout ->
						$scope.originMarker = L.marker($scope.origin,{icon: $scope.greenIcon}).addTo($scope.map).bindPopup("C'était ici!").openPopup();
					,1000
					return

				$scope.afficheScore = (obj) ->
					$('#partieDebut').hide();
					$scope.markerTry.setLatLng(obj.latlng).addTo($scope.map);
					$scope.distance = $scope.markerTry.getLatLng().distanceTo($scope.origin);
					$scope.calculScore(($scope.distanceDiff)/3,(10/3);
					$scope.scoreTotal += $scope.scoreManche;
					$scope.timeManche = $scope.seconds;
					$scope.$apply();
					return


				$scope.calculScore = (dist, time) ->
					if dist 
						if $scope.distance <= dist
							$scope.scoreManche = 5;
						if $scope.distance <= 2*dist && $scope.distance > dist
							$scope.scoreManche = 3;
						if $scope.distance <= 3*dist && $scope.distance > 2*dist
							$scope.scoreManche = 1;
						if $scope.distance > 3*dist
							$scope.scoreManche = 0;
					if time 
						if $scope.timerValue <= time
							$scope.scoreManche *= 4;
						if $scope.timerValue <= 2*time && $scope.timerValue > time
							$scope.scoreManche *= 2;
						if $scope.timerValue <= 3*time && $scope.timerValue > 2*time
							$scope.scoreManche *= 1;
						if $scope.timerValue > 3*time
							$scope.scoreManche *= 0;
							return

				$scope.resetMap = ->
					$scope.map.removeLayer($scope.markerTry);
					$scope.map.removeLayer($scope.originMarker);
					$scope.$apply();
					return

				$scope.verifNbManche = ->
					if $scope.nbManche < $scope.nbMancheMax
						## ----------------------------Intération des images
						
						$scope.photoone = Session.get('arrayPhoto')[$scope.nbManche].href
						###$scope.lat = Session.get('arrayPhoto')[$scope.nbManche].lat
						$scope.lng = Session.get('arrayPhoto')[$scope.nbManche].lng###
						$scope.origin = [Session.get('arrayPhoto')[$scope.nbManche].lat, Session.get('arrayPhoto')[$scope.nbManche].lng]

						$('#scoreManche').hide();
						$('#mancheSuivante').show();
						return
					else
						$scope.finPartie();	
						return


				$scope.startTimer = ->
					$scope.$broadcast('timer-start');
					$scope.timerRunning = true;
					return

				$scope.stopTimer = ->
					$scope.$broadcast('timer-stop');
					$scope.timerRunning = false;
					return

				$scope.resetTimer = ->
					$scope.$broadcast('timer-reset');
					$scope.timerRunning = false;
					return
				
				$scope.$on 'timer-tick', (event, args) ->  
					$scope.timerValue = args.millis + '';
					$scope.timerValue = parseInt ($scope.timerValue)/1000;
					$scope.timerValue = 10 - $scope.timerValue
					return


				## ---------------------------- Fin de la partie / Proposer d'enregistrer son highscore ! 
				$scope.finPartie = ->
					$scope.stopTimer();
					setTimeout ->
						$scope.resetMap();				
						$('#scoreManche').hide();
						$('#scoreFinal').show();
						return
					,0
					## ----------------------------Enrigstrement du score
					## ----------------------------Enrigstrement du score
					## ----------------------------Enrigstrement du score
					$http({
						method: 'PUT',
						url: '/PhotoLocate/service/play/games/'+Session.get('id')+"?apiKey="+Session.get('token'),
						data: {status: 'Finish', score: $scope.scoreTotal},
						# headers: {'Content-Type': 'application/json'}
					}).success  ->
						console.log('ok')
					.error -> 
						console.log('error')
						return
					return
				return


				

		.error -> 
			console.log($scope.games)
		



	## ----------------------------Affichage des meilleurs score
	## ----------------------------Affichage des meilleurs score
	## ----------------------------Affichage des meilleurs score
	$scope.GetHighScore = ->
		$http({
			method: 'GET',
			url: '/PhotoLocate/service/play/games/score/'+$scope.level+"?ville="+$scope.ville,
			# data: {status: 7330, score: 1},
			# headers: {'Content-Type': 'application/json'}
		}).success (HighScore)  ->
			console.log('ok')
			# console.log(HighScore)
			$scope.highscore = HighScore
			console.log(HighScore)
		.error -> 
			console.log('error')
			console.log(level)



