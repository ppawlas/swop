(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('IndicatorController', IndicatorController);

    function IndicatorController(indicators) {

        var vm = this;

        vm.indicators = indicators.data;
    }

})();