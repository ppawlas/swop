(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportPreviewController', ReportPreviewController);

    function ReportPreviewController(report, ReportService) {

        var vm = this;

        vm.title = 'REPORT_PREVIEW';

        vm.error = '';

        vm.report = ReportService.helpers.preprocess(report.data);
        vm.indicatorsColumns = ReportService.helpers.getIndicatorsColumns(report.data);
        vm.indicatorsPivotColumns = ReportService.helpers.getIndicatorsPivotColumns(report.data);

    }

})();