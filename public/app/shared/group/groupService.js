(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('GroupService', GroupService);

    function GroupService($http) {
        var GroupService = {};

        GroupService.getAll = function() {
            // Return an $http request for all groups
            return $http.get('api/manager/groups');
        };

        GroupService.get = function(groupId) {
            // Return an $http request for selected group
            return $http.get('api/manager/groups/' + groupId);
        };

        GroupService.update = function(groupId, group) {
            // Return an $http request for updating selected group
            return $http.put('api/manager/groups/' + groupId, group);
        };

        GroupService.create = function(group) {
            // Return an $http request for creating new group
            return $http.post('api/manager/groups', group);
        };

        GroupService.delete = function(groupId) {
            // Return an $http request for deleting selected group
            return $http.delete('api/manager/groups/' + groupId);
        };

        GroupService.helpers = {};

        /**
         * Filter the given array of User objects only to those that are not yet
         * assigned to the given Group object.
         * @param group group Object
         * @param users array of User objects
         * @returns filtered array of User objects
         */
        GroupService.helpers.filterUsers = function(group, users) {
            var groupUsersIds = group.users.map(function(user) { return user.id; });
            return users.filter(function(user) { return groupUsersIds.indexOf(user.id) === -1; });
        }

        /**
         * Filter the given array of Indicator objects only to those that are not yet
         * assigned to the given Group object.
         * @param group group Object
         * @param indicators array of Indicator objects
         * @returns filtered array of Indicator objects
         */
        GroupService.helpers.filterIndicators = function(group, indicators) {
            var groupIndicatorsIds = group.indicators.map(function(indicator) { return indicator.id; });
            return indicators.filter(function(indicator) { return groupIndicatorsIds.indexOf(indicator.id) === -1; });
        }


        return GroupService;
    }

})();
