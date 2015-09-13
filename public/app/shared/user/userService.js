(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('UserService', UserService);

    function UserService($http) {
        var UserService = {};

        UserService.getAll = function() {
            // Return an $http request for all user
            return $http.get('api/authenticate');
        };

        return UserService;
    }

})();