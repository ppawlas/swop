(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('GroupController', GroupController);

    function GroupController($state, groups, GroupService, MessageService) {

        var vm = this;

        vm.message = MessageService.getMessage();
        vm.error = '';

        vm.groups = groups.data;

        vm.new = function() {
            $state.go('group-new');
        };

        vm.delete = function(groupId) {
            GroupService.delete(groupId).then(function(response) {
                vm.error = '';
                vm.message = 'Data has been removed successfully'
                GroupService.getAll().then(function(response) {
                    vm.groups = response.data;
                });
            }, function(error) {
                vm.message = '';
                vm.error = 'Data has not been removed successfully'
            });
        };

    }

})();