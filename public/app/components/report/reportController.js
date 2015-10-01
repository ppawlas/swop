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
                vm.message = 'DATA_REMOVE_SUCCESS'
                ReportService.getAll().then(function(response) {
                    vm.reports = response.data;
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'DATA_REMOVE_ERROR'
            });
        };

        vm.generate = function(reportId) {
            ReportService.generate(reportId).then(function(response) {
                $state.go('report-view', { reportId: reportId });
            }, function(error) {
                vm.message = '';
                vm.error = 'GENERATION_ERROR';
            });
        };

    }

})();