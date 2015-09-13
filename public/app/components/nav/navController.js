(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('NavController', NavController);

    function NavController(AuthService) {

        var vm = this;

        vm.logout = AuthService.logout;
    }

})();