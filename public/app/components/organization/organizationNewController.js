(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationNewController', OrganizationNewController);

    function OrganizationNewController($state, OrganizationService, MessageService) {

        var vm = this;

        vm.edit = false;

        vm.title = 'New organization';
        vm.organization = {};

        vm.error = '';

        vm.submit = function() {
            OrganizationService.create(vm.organization).then(function(response) {
                MessageService.setMessage('Data has been saved successfully');
                $state.go('organizations');
            }, function(error) {
                vm.error = 'Data has not been saved successfully'
            });
        }
    }

})();