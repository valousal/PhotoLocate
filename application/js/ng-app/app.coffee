app = angular.module('Application', ['ngRoute', 'ngResource', 'timer']);

app.config ($routeProvider, $locationProvider) ->
    $routeProvider
        .when('/', {
            templateUrl: 'partials/home.html',
            controller: 'GameController'
        })

        .when('/games/', {
            templateUrl: 'partials/game.html',
            controller: 'GameController'
        })


        .when('/options', {
            templateUrl: 'partials/options.html',
            controller: 'GameController'
        })

        .when('/score', {
            templateUrl: 'partials/score.html',
            controller: 'GameController'
        })
        .otherwise({ redirectTo: '/' })