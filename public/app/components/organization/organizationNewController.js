(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationNewController', OrganizationNewController);

    function OrganizationNewController($state, OrganizationService) {

        var vm = this;

        vm.title = 'New organization';
        vm.organization = {};

        vm.error = '';

        vm.submit = function() {
            OrganizationService.create(vm.organization).then(function(response) {
                $state.go('organizations');
            }, function(error) {
                vm.error = 'Data has not been save successfully'
            });
        }
    }

})();