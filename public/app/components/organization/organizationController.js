(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationController', OrganizationController);

    function OrganizationController($state, $stateParams, organizations, OrganizationService) {

        var vm = this;

        vm.message = $stateParams.message;
        vm.error = $stateParams.error;

        vm.organizations = organizations.data;

        vm.new = function() {
            $state.go('organization-new');
        }

        vm.delete = function(organizationId) {
            OrganizationService.delete(organizationId).then(function(response) {
                vm.error = '';
                vm.message = 'Data has been removed successfully'
                OrganizationService.getAll().then(function(response) {
                    vm.organizations = response.data;
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'Data has not been removed successfully'
            });
        }

    }

})();