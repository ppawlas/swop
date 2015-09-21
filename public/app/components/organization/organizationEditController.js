(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationEditController', OrganizationEditController);

    function OrganizationEditController(organization, OrganizationService) {

        var vm = this;

        vm.title = 'Organization details';
        vm.organization = organization.data;

        vm.message = '';
        vm.error = '';

        vm.submit = function() {
            OrganizationService.update(vm.organization.id, vm.organization).then(function(response) {
                vm.error = '';
                vm.message = 'Data has been saved successfully'
            }, function(error) {
                vm.message = '';
                vm.error = 'Data has not been save successfully'
            });
        }
    }

})();