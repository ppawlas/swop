(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('IndicatorService', IndicatorService);

    function IndicatorService($http) {
        var IndicatorService = {};

        IndicatorService.getAll = function() {
            // Return an $http request for all organizations
            return $http.get('api/admin/indicators');
        };

        IndicatorService.getForOrganization = function() {
            // Return an $http request for all organizations
            return $http.get('api/manager/indicators');
        };

        return IndicatorService;
    }

})();
