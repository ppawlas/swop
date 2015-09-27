(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportEditController', ReportEditController);

    function ReportEditController($state, report, users, indicators, groups, ReportService, MessageService, GroupService) {

        var vm = this;

        vm.title = 'Report details';
        vm.report = ReportService.helpers.preprocess(report.data);
        vm.users = ReportService.helpers.filterUsers(report.data, users.data);
        vm.indicators = ReportService.helpers.filterIndicators(report.data, indicators.data);
        vm.groups = groups.data;
        vm.group;

        vm.datepicker = {
            status: {
                opened: false
            },
            options: {
                startingDay: 1
            },
            open: function ($event) {
                vm.datepicker.status.opened = true;
            }
        };

        vm.toggles = {
            users: {
                self: {
                    state: false,
                    toggle: function() {
                        vm.report.users.forEach(function (user) {
                            user.pivot = user.pivot || {};
                            user.pivot.view_self = vm.toggles.users.self.state;
                        });
                    }
                },
                all: {
                    state: false,
                    toggle: function() {
                        vm.report.users.forEach(function (user) {
                            user.pivot = user.pivot || {};
                            user.pivot.view_all = vm.toggles.users.all.state;
                        });
                    }
                }
            },
            indicators: {
                value: {
                    state: false,
                    toggle: function() {
                        vm.report.indicators.forEach(function (indicator) {
                            indicator.pivot.show_value = vm.toggles.indicators.value.state;
                        });
                    }
                },
                points: {
                    state: false,
                    toggle: function() {
                        vm.report.indicators.forEach(function (indicator) {
                            indicator.pivot.show_points = vm.toggles.indicators.points.state;
                        });
                    }
                }
            }
        };

        vm.alerts = [];

        vm.closeAlert = function(index) {
            vm.alerts.splice(index, 1);
        };

        vm.addUser = function(index) {
            vm.report.users.push(vm.users[index]);
            vm.users.splice(index, 1);
        };

        vm.removeUser = function(index) {
            vm.users.push(vm.report.users[index]);
            vm.report.users.splice(index, 1);
        };

        vm.addIndicator = function(index) {
            vm.report.indicators.push(vm.indicators[index]);
            vm.indicators.splice(index, 1);
        };

        vm.removeIndicator = function(index) {
            vm.indicators.push(vm.report.indicators[index]);
            vm.report.indicators.splice(index, 1);
        };

        vm.toggleGroupPanel = function() {
            vm.groupPanel = !vm.groupPanel;
        };

        vm.loadGroup = function() {
            GroupService.get(vm.group.id).then(function(response) {
                vm.report.users = response.data.users;
                vm.report.indicators = response.data.indicators;

                vm.users = ReportService.helpers.filterUsers(vm.report, users.data);
                vm.indicators = ReportService.helpers.filterIndicators(vm.report, indicators.data);

                vm.alerts.push({ type: 'success', msg: 'Group has been loaded successfully' });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'Group has not been loaded successfully' });
            })
        };

        vm.saveGroup = function() {
            var group = {};
            angular.copy(vm.group, group);
            group.users = vm.report.users;
            group.indicators = vm.report.indicators;

            GroupService.update(group.id, group).then(function(response) {
                vm.alerts.push({ type: 'success', msg: 'Group has been saved successfully' });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'Group has not been saved successfully' });
            });
        };

        vm.saveNewGroup = function() {
            var group = {};
            group.name = vm.newGroupName;
            group.users = vm.report.users;
            group.indicators = vm.report.indicators;

            GroupService.create(group).then(function(response) {
                vm.alerts.push({ type: 'success', msg: 'Group has been saved successfully' });
                GroupService.getAll().then(function(response) {
                    vm.groups = response.data;
                });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'Group has not been saved successfully' });
            });
        };

        vm.save = function(callback) {
            ReportService.update(vm.report.id, vm.report).then(function(response) {

                if (callback) {
                    callback();
                } else {
                    MessageService.setMessage('Data has been saved successfully');
                    $state.go('reports');
                }

            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'Data has not been saved successfully' });
            });
        };

        vm.preview = function() {
            vm.save(function() {
                $state.go('report-preview', { reportId: vm.report.id });
            });
        };

        vm.view = function() {
            vm.save(function() {
                $state.go('report-view', { reportId: vm.report.id });
            });
        };

        vm.generate = function() {
            vm.save(function() {
                ReportService.generate(vm.report.id).then(function(response) {
                    $state.go('report-view', { reportId: vm.report.id });
                }, function(error) {
                    vm.alerts.push({ type: 'danger', msg: 'Report has not been generated successfully' });
                });
            });
        };
    }

})();