###angular.module('Application').factory 'Games', ($resource, $http, $location) ->
	class Games
		create: (attrs, successHandler) ->
			$http({
				method: 'POST',
				url: '/PhotoLocate/service/play/games/',
				data: "message",
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			});
###
		