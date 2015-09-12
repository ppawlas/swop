(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('OrganizationService', OrganizationService);

    function OrganizationService($http) {
        var OrganizationService = {};

        OrganizationService.getAll = function() {
            // Return an $http request for all organizations
            return $http.get('api/organizations');
        };

        return OrganizationService;
    }

})();