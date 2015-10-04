(function() {

    'use strict';

    angular
        .module('authApp')
        .controller('ReportEditController', ReportEditController);

    function ReportEditController($state, $confirm, report, users, indicators, groups, ReportService, MessageService, GroupService) {

        var vm = this;

        vm.new = false;

        vm.title = 'REPORT_DETAILS';
        vm.report = ReportService.helpers.preprocess(angular.copy(report.data));
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

        vm.loadGroup = function() {
            GroupService.get(vm.group.id).then(function(response) {
                vm.report.users = response.data.users;
                vm.report.indicators = response.data.indicators;

                vm.users = ReportService.helpers.filterUsers(vm.report, users.data);
                vm.indicators = ReportService.helpers.filterIndicators(vm.report, indicators.data);

                vm.alerts.push({ type: 'success', msg: 'GROUP_LOAD_SUCCESS' });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'GROUP_LOAD_ERROR' });
            })
        };

        vm.saveGroup = function() {
            var group = {};
            angular.copy(vm.group, group);
            group.users = vm.report.users;
            group.indicators = vm.report.indicators;

            GroupService.update(group.id, group).then(function(response) {
                vm.alerts.push({ type: 'success', msg: 'GROUP_SAVE_SUCCESS' });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'GROUP_SAVE_ERROR' });
            });
        };

        vm.saveNewGroup = function() {
            var group = {};
            group.name = vm.newGroupName;
            group.users = vm.report.users;
            group.indicators = vm.report.indicators;

            GroupService.create(group).then(function(response) {
                vm.alerts.push({ type: 'success', msg: 'GROUP_SAVE_SUCCESS' });
                GroupService.getAll().then(function(response) {
                    vm.groups = response.data;
                });
            }, function(error) {
                vm.alerts.push({ type: 'danger', msg: 'GROUP_SAVE_ERROR' });
            });
        };

        vm.save = function(callback) {
            var save = function() {
                ReportService.update(vm.report.id, vm.report).then(function(response) {

                    if (callback) {
                        callback();
                    } else {
                        MessageService.setMessage('DATA_SAVE_SUCCESS');
                        $state.go('reports');
                    }

                }, function(error) {
                    vm.alerts.push({ type: 'danger', msg: 'DATA_SAVE_ERROR' });
                });
            };

            if (ReportService.helpers.isReportChanged(report.data, vm.report)) {
                $confirm({text: 'Parametry raportu uległy zmianie. Poprzednie wyniki zostaną utracone. Czy chcesz kontynuować?'}).then(function() {
                    ReportService.reset(vm.report.id).then(function(response) {
                        save();
                    }, function(error) {
                        vm.alerts.push({ type: 'danger', msg: 'REPORT_RESULTS_DELETE_ERROR' });
                    });
                });
            } else {
                save();
            }

        };

        vm.preview = function() {
            vm.save(function() {
                $state.go('report-preview', { reportId: vm.report.id });
            });
        };

        vm.view = function() {
            vm.save(function() {
                if (ReportService.helpers.isReportChanged(report.data, vm.report)) {
                    ReportService.generate(vm.report.id).then(function(response) {
                        $state.go('report-view', { reportId: vm.report.id });
                    }, function(error) {
                        vm.alerts.push({ type: 'danger', msg: 'GENERATION_ERROR' });
                    });
                } else {
                    $state.go('report-view', {reportId: vm.report.id});
                }
            });
        };

        vm.generate = function() {
            vm.save(function() {
                ReportService.generate(vm.report.id).then(function(response) {
                    $state.go('report-view', { reportId: vm.report.id });
                }, function(error) {
                    vm.alerts.push({ type: 'danger', msg: 'GENERATION_ERROR' });
                });
            });
        };
    }

})();