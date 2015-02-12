angular.module('Application').factory('Session', ->
	savedData = new Array

	{
		set: (key, data) -> 
			savedData[key] = data
		get: (key) ->
			savedData[key]
	}

)