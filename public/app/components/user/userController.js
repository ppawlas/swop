(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('UserController', UserController);

    function UserController($http, AuthService, users) {

        var vm = this;

        vm.users = users.data;

    }

})();