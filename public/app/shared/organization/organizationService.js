(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('OrganizationService', OrganizationService);

    function OrganizationService($http) {
        var OrganizationService = {};

        OrganizationService.getAvailable = function() {
            // Return an $http request for all available organizations
            // (it is assumed that this action does not require token)
            return $http.get('api/authenticate/organizations');
        };

        OrganizationService.getAll = function() {
            // Return an $http request for all organizations
            return $http.get('api/admin/organizations');
        };

        OrganizationService.get = function(organizationId) {
            // Return an $http request for selected organization
            return $http.get('api/admin/organizations/' + organizationId);
        };

        OrganizationService.update = function(organizationId, organization) {
            // Return an $http request for updating selected organization
            return $http.put('api/admin/organizations/' + organizationId, organization);
        }

        OrganizationService.create = function(organization) {
            // Return an $http request for creating new organization
            return $http.post('api/admin/organizations', organization);
        }

        OrganizationService.delete = function(organizationId) {
            // Return an $http request for deleting selected organization
            return $http.delete('api/admin/organizations/' + organizationId);
        }

        return OrganizationService;
    }

})();