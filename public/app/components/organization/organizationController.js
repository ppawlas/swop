(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationController', OrganizationController);

    function OrganizationController($state, organizations, OrganizationService, MessageService) {

        var vm = this;

        vm.message = MessageService.getMessage();
        vm.error = '';

        vm.organizations = organizations.data;

        vm.new = function() {
            $state.go('organization-new');
        };

        vm.delete = function(organizationId) {
            OrganizationService.delete(organizationId).then(function(response) {
                vm.error = '';
                vm.message = 'DATA_REMOVE_SUCCESS'
                OrganizationService.getAll().then(function(response) {
                    vm.organizations = response.data;
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'DATA_REMOVE_ERROR'
            });
        };

    }

})();