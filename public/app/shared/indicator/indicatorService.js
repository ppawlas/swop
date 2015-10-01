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

        IndicatorService.update = function(indicatorId, indicator) {
            // Return an $http request for updating selected indicator
            return $http.put('api/manager/indicators/' + indicatorId, indicator);
        };

        IndicatorService.helpers = {};

        /**
         * Prepare the given Indicators array converting its coefficients strings
         * to numbers.
         * @param indicators Indicators array
         * @returns prepared indicators array
         */
        IndicatorService.helpers.preprocess = function(indicators) {
            indicators.forEach(function(indicator) {
                indicator.pivot.coefficient = Number(indicator.pivot.coefficient);
            });

            return indicators;
        };

        return IndicatorService;
    }

})();
