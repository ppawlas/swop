(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationEditController', OrganizationEditController);

    function OrganizationEditController($state, organization, OrganizationService, MessageService) {

        var vm = this;

        vm.edit = true;

        vm.title = 'Organization details';
        vm.organization = organization.data;

        vm.error = '';

        vm.submit = function() {
            OrganizationService.update(vm.organization.id, vm.organization).then(function(response) {
                MessageService.setMessage('Data has been saved successfully');
                $state.go('organizations');
            }, function(error) {
                vm.error = 'Data has not been saved successfully'
            });
        };
    }

})();