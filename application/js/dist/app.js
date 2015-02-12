// Generated by CoffeeScript 1.8.0
(function() {
  var app;

  app = angular.module('Application', ['ngRoute', 'ngResource', 'timer']);

  app.config(function($routeProvider, $locationProvider) {
    return $routeProvider.when('/', {
      templateUrl: 'partials/home.html',
      controller: 'GameController'
    }).when('/games/', {
      templateUrl: 'partials/game.html',
      controller: 'GameController'
    }).when('/options', {
      templateUrl: 'partials/options.html',
      controller: 'GameController'
    }).when('/score', {
      templateUrl: 'partials/score.html',
      controller: 'GameController'
    }).otherwise({
      redirectTo: '/'
    });
  });

}).call(this);
