(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('OrganizationController', OrganizationController);

    function OrganizationController(organizations) {

        var vm = this;

        vm.organizations = organizations.data;

    }

})();