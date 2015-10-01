(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationNewController', OrganizationNewController);

    function OrganizationNewController($state, OrganizationService, MessageService) {

        var vm = this;

        vm.edit = false;

        vm.title = 'NEW_ORGANIZATION';
        vm.organization = {};

        vm.error = '';

        vm.submit = function() {
            OrganizationService.create(vm.organization).then(function(response) {
                MessageService.setMessage('DATA_SAVE_SUCCESS');
                $state.go('organizations');
            }, function(error) {
                vm.error = 'DATA_SAVE_ERROR'
            });
        }
    }

})();