(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('GroupEditController', GroupEditController);

    function GroupEditController($state, group, users, indicators, GroupService, MessageService) {

        var vm = this;

        vm.title = 'Group details';
        vm.group = group.data;
        vm.users = GroupService.helpers.filterUsers(group.data, users.data);
        vm.indicators = GroupService.helpers.filterIndicators(group.data, indicators.data);

        vm.error = '';

        vm.addUser = function(index) {
            vm.group.users.push(vm.users[index]);
            vm.users.splice(index, 1);
        };

        vm.removeUser = function(index) {
            vm.users.push(vm.group.users[index]);
            vm.group.users.splice(index, 1);
        };

        vm.addIndicator = function(index) {
            vm.group.indicators.push(vm.indicators[index]);
            vm.indicators.splice(index, 1);
        };

        vm.removeIndicator = function(index) {
            vm.indicators.push(vm.group.indicators[index]);
            vm.group.indicators.splice(index, 1);
        };

        vm.submit = function() {
            GroupService.update(vm.group.id, vm.group).then(function(response) {
                MessageService.setMessage('Data has been saved successfully');
                $state.go('groups');
            }, function(error) {
                vm.error = 'Data has not been saved successfully'
            });
        };
    }

})();