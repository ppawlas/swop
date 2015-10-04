(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportViewController', ReportViewController);

    function ReportViewController($auth, report, results, ReportService) {

        var vm = this;

        vm.title = 'REPORT_RESULTS';

        vm.error = '';

        vm.report = ReportService.helpers.preprocess(report.data);

        vm.table = results.data;

        vm.visible = function(component) {
            return component.visible;
        };

        vm.comparator = function(user) {
            return user.sum;
        };

        vm.color = function(result, indicatorId, resultType) {

            var min = vm.table.statistics.min.indicators[indicatorId][resultType].data;
            var max = vm.table.statistics.max.indicators[indicatorId][resultType].data;

            var dangerThreshold = min + (max - min) / 3;
            var successThreshold = max - (max - min) / 3;

            if (result < dangerThreshold) {
                return 'bg-danger';
            } else if (result > successThreshold) {
                return 'bg-success';
            } else {
                return 'bg-warning';
            }
        };

        vm.excelUrl = function() {
            return ReportService.helpers.getExcelUrl(vm.report.id);
        };

        vm.pdfUrl = function() {
            return ReportService.helpers.getPdfUrl(vm.report.id);
        };

    }

})();