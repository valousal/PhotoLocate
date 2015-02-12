app = angular.module('Application')
app.controller 'GameController', ($scope,$rootScope, $resource,$http,$location,$routeParams,Session) ->


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

		}).success (games) ->
			$scope.games = games;
			# Partie:
			$scope.scoreTotal = 0;
			$scope.scoreManche = 0;
			$scope.nbMancheMax = 1;
			$scope.nbManche = 0;
			$scope.timerValue = 0;

			# Map
			$scope.map = L.map('map').setView([10, 10], 13); # $scope.games.lat, $scope.games.lng
			$scope.markerTry = L.marker();
			$scope.distance = 0;
			$scope.origin = [10, 10]; # $scope.games.lat, $scope.games.lng

			L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png',{
			}).addTo($scope.map);


			## -------------------------- Affichage des pictures 
			return $scope.GetPictures().then (response) -> 
				Session.set('pictures', response.data)
				console.log(Session.get('pictures'))
				$scope.pictures = response.data

			## ----------------------------Déroulement du jeu 
				$scope.debutPartie = ->
					$('#scoreManche').hide();
					$('#partieDebut').hide();
					$('#mancheSuivante').show()

				$scope.mancheSuivante = ->
					$scope.scoreManche = 0;
					$scope.resetTimer();
					$scope.verifNbManche();

				$scope.go = ->
					$scope.nbManche += 1;
					$('#mancheSuivante').hide();
					$('#shadow').hide();
					$scope.markerTry = L.marker();
					$scope.distance = 0;
					$scope.startTimer();

				$scope.finManche = (obj) ->
					$scope.stopTimer();
					$scope.afficheScore(obj);
					$scope.showOrigin();
					setTimeout ->
						$scope.resetMap();				
						$('#shadow').show();
						$('#scoreManche').show();
					,3000

				$scope.noTime = ->
					$scope.stopTimer();
					$scope.showOrigin();
					setTimeout ->
						$scope.resetMap();				
						$('#shadow').show();
						$('#scoreManche').show();
					,3000


				$scope.showOrigin = ->
					setTimeout ->
						$scope.originMarker = L.circle($scope.origin, 15, {
											    color: 'red',
											    fillColor: '#red',
											    fillOpacity: 0.5
											}).addTo($scope.map).bindPopup("C'était ici!").openPopup();
					,1000

				$scope.afficheScore = (obj) ->
					$scope.markerTry.setLatLng(obj.latlng).addTo($scope.map);
					$scope.distance = $scope.markerTry.getLatLng().distanceTo($scope.origin);
					$scope.calculScore(1000,3);
					$scope.scoreTotal += $scope.scoreManche;
					$scope.timeManche = $scope.seconds;
					$scope.$apply();


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

				$scope.resetMap = ->
					$scope.map.removeLayer($scope.markerTry);
					$scope.map.removeLayer($scope.originMarker);
					$scope.$apply();

				$scope.verifNbManche = ->
					if $scope.nbManche < $scope.nbMancheMax
						$('#scoreManche').hide();
						$('#mancheSuivante').show();
					else
						$scope.finPartie();	


				$scope.startTimer = ->
					$scope.$broadcast('timer-start');
					$scope.timerRunning = true;

				$scope.stopTimer = ->
					$scope.$broadcast('timer-stop');
					$scope.timerRunning = false;

				$scope.resetTimer = ->
					$scope.$broadcast('timer-reset');
					$scope.timerRunning = false;

				$scope.$on('timer-tick', (event, args) ->  
					$scope.timerValue = args.millis +'';
					$scope.timerValue = parseInt ($scope.timerValue)/1000;
					$scope.timerValue = 10 - $scope.timerValue)



				## ---------------------------- Fin de la partie / Proposer d'enregistrer son highscore ! 
				$scope.finPartie = ->
					$scope.stopTimer();
					setTimeout ->
						$scope.resetMap();				
						$('#scoreManche').hide();
						$('#scoreFinal').show();
					,0

				

		.error -> 
			console.log($scope.games)

	

	## ----------------------------Enrigstrement du score
	## ----------------------------Enrigstrement du score
	## ----------------------------Enrigstrement du score
	$scope.PutGame = ->
		$http({
			method: 'PUT',
			url: '/PhotoLocate/service/play/games/72?apiKey='+Session.get(),
			data: {status: 7330, score: 1},
			headers: {'Content-Type': 'application/json'}
		}).success  ->
			console.log('ok')
		.error -> 
			console.log('error')



	## ----------------------------Affichage des meilleurs score
	## ----------------------------Affichage des meilleurs score
	## ----------------------------Affichage des meilleurs score
	$scope.GetHighScore = ->
		###$http({
			method: 'GET',
			url: '/PhotoLocate/service/play/games/72?apiKey='+Session.get(),
			data: {status: 7330, score: 1},
			headers: {'Content-Type': 'application/json'}
		}).success  ->
			console.log('ok')
		.error -> 
			console.log('error')###



