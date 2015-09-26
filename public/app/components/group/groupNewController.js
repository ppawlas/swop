(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('GroupNewController', GroupNewController);

    function GroupNewController($state, GroupService, MessageService, users, indicators) {

        var vm = this;

        vm.title = 'New group';
        vm.group = {
            users: [],
            indicators: []
        };
        vm.users = users.data;
        vm.indicators = indicators.data;

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
            GroupService.create(vm.group).then(function(response) {
                MessageService.setMessage('Data has been saved successfully');
                $state.go('groups');
            }, function(error) {
                vm.error = 'Data has not been saved successfully'
            });
        }
    }

})();