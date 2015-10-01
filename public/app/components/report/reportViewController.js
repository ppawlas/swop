(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportViewController', ReportViewController);

    function ReportViewController(report, results, ReportService) {

        var vm = this;

        vm.title = 'REPORT_RESULTS';

        vm.error = '';

        vm.report = ReportService.helpers.preprocess(report.data);
        vm.indicatorsColumns = ReportService.helpers.getIndicatorsColumns(report.data);
        vm.indicatorsPivotColumns = ReportService.helpers.getIndicatorsPivotColumns(report.data);
        vm.userResults = ReportService.helpers.getUserResults(results.data);
        vm.usersStatistics = ReportService.helpers.getUsersStatistics(results.data, report.data);
        vm.indicatorsStatistics = ReportService.helpers.getIndicatorsStatistics(results.data);
        vm.globalStatistics = ReportService.helpers.getGlobalStatistics(vm.usersStatistics);

        vm.getColorClass = function(userId, indicatorId, resultType) {
            var result = vm.userResults[userId][indicatorId][resultType];
            var min = vm.indicatorsStatistics[indicatorId][resultType].min;
            var max = vm.indicatorsStatistics[indicatorId][resultType].max;

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

        vm.sumComparator = function(user) {
            return vm.usersStatistics[user.id].sum;
        };

    }

})();