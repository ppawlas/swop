(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('IndicatorManagerController', IndicatorManagerController);

    function IndicatorManagerController(indicators, IndicatorService) {

        var vm = this;

        vm.message = '';
        vm.error = '';

        vm.indicators = IndicatorService.helpers.preprocess(indicators.data);

        vm.setCoefficient = function(indicator) {
            IndicatorService.update(indicator.id, indicator).then(function(response) {
                vm.error = '';
                vm.message = 'DATA_SAVE_SUCCESS'
                IndicatorService.getForOrganization().then(function(response) {
                    vm.indicators = IndicatorService.helpers.preprocess(response.data);
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'DATA_SAVE_ERROR'
            });
        };
    }

})();
