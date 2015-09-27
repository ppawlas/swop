(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportViewController', ReportViewController);

    function ReportViewController(report, results, ReportService) {

        var vm = this;

        vm.title = 'Report results';

        vm.error = '';

        vm.report = ReportService.helpers.preprocess(report.data);
        vm.indicatorsColumns = ReportService.helpers.getIndicatorsColumns(report.data);
        vm.indicatorsPivotColumns = ReportService.helpers.getIndicatorsPivotColumns(report.data);
        vm.userResults = ReportService.helpers.getUserResults(results.data);

    }

})();