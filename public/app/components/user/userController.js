(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('UserController', UserController);

    function UserController(AuthService, users) {

        var vm = this;

        vm.users = users.data;

    }

})();