(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportController', ReportController);

    function ReportController($state, reports, ReportService, MessageService) {

        var vm = this;

        vm.message = MessageService.getMessage();
        vm.error = '';

        vm.reports = reports.data;

        vm.new = function() {
            $state.go('report-new');
        };

        vm.delete = function(reportId) {
            ReportService.delete(reportId).then(function(response) {
                vm.error = '';
                vm.message = 'Data has been removed successfully'
                ReportService.getAll().then(function(response) {
                    vm.reports = response.data;
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'Data has not been removed successfully'
            });
        };

        vm.generate = function(reportId) {
            ReportService.generate(reportId).then(function(response) {
                $state.go('report-view', { reportId: reportId });
            }, function(error) {
                vm.message = '';
                vm.error = 'Report has not been generated successfully';
            });
        };

    }

})();