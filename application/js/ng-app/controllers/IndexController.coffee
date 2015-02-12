app = angular.module('Application');
app.controller 'IndexController',  ($scope, $location) ->
    $scope.protocole = $location.protocol()
    $scope.host = $location.host()