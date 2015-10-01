(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationEditController', OrganizationEditController);

    function OrganizationEditController($state, organization, OrganizationService, MessageService) {

        var vm = this;

        vm.edit = true;

        vm.title = 'ORGANIZATION_DETAILS';
        vm.organization = organization.data;

        vm.error = '';

        vm.submit = function() {
            OrganizationService.update(vm.organization.id, vm.organization).then(function(response) {
                MessageService.setMessage('DATA_SAVE_SUCCESS');
                $state.go('organizations');
            }, function(error) {
                vm.error = 'DATA_SAVE_ERROR'
            });
        };
    }

})();