(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('AuthService', AuthService);

    function AuthService($auth, $rootScope, $state) {
        var AuthService = {};

        AuthService.logout = function() {
            $auth.logout().then(function() {
                // Remove the authenticated user from local storage
                localStorage.removeItem('user');

                // Flip authenticated to false so that we no longer
                // show UI elements dependant on the user being logged in
                $rootScope.authenticated = false;

                // Remove the current user info from rootscope
                $rootScope.currentUser = null;

                // Redirect to the login page
                $state.go('auth');
            });
        };

        return AuthService;
    }

})();