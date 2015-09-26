(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('UserService', UserService);

    function UserService($http) {
        var UserService = {};

        UserService.getAll = function() {
            // Return an $http request for all users
            return $http.get('api/admin/users');
        };

        UserService.getForOrganization = function() {
            // Return an $http request for all users from current organization
            return $http.get('api/manager/users');
        };

        return UserService;
    }

})();