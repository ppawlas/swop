(function() {

    'use strict';

    angular
        .module('authApp')
        .factory('ReportService', ReportService);

    function ReportService($http, $auth) {
        var ReportService = {};

        ReportService.getAll = function() {
            // Return an $http request for all reports
            return $http.get('api/manager/reports');
        };

        ReportService.get = function(reportId) {
            // Return an $http request for selected report
            return $http.get('api/manager/reports/' + reportId);
        };

        ReportService.getResults = function(reportId) {
            // Return an $http request for selected report results
            return $http.get('api/manager/reports/' + reportId + '/results');
        };

        ReportService.generate = function(reportId) {
            // Return an $http request for generating report results
            return $http.post('api/manager/reports/' + reportId + '/evaluate');
        };

        ReportService.reset = function(reportId) {
            // Return an $http request for resetting report results
            return $http.post('api/manager/reports/' + reportId + '/reset');
        };

        ReportService.update = function(reportId, report) {
            // Return an $http request for updating selected report
            return $http.put('api/manager/reports/' + reportId, ReportService.helpers.postprocess(report));
        };

        ReportService.create = function(report) {
            // Return an $http request for creating new report
            return $http.post('api/manager/reports', ReportService.helpers.postprocess(report));
        };

        ReportService.delete = function(reportId) {
            // Return an $http request for deleting selected report
            return $http.delete('api/manager/reports/' + reportId);
        };

        ReportService.helpers = {};

        ReportService.helpers.getExcelUrl = function(reportId) {
            return '/api/manager/reports/' + reportId + '/excel?token=' + $auth.getToken();
        };

        ReportService.helpers.getPdfUrl = function(reportId) {
            return '/api/manager/reports/' + reportId + '/pdf?token=' + $auth.getToken();
        };

        /**
         * Filter the given array of User objects only to those that are not yet
         * assigned to the given Report object.
         * @param report Report object
         * @param users array of User objects
         * @returns filtered array of User objects
         */
        ReportService.helpers.filterUsers = function(report, users) {
            var reportUsersIds = report.users.map(function(user) { return user.id; });
            return users.filter(function(user) { return reportUsersIds.indexOf(user.id) === -1; });
        };

        /**
         * Filter the given array of Indicator objects only to those that are not yet
         * assigned to the given Report object.
         * @param report Report object
         * @param indicators array of Indicator objects
         * @returns filtered array of Indicator objects
         */
        ReportService.helpers.filterIndicators = function(report, indicators) {
            var reportIndicatorsIds = report.indicators.map(function(indicator) { return indicator.id; });
            return indicators.filter(function(indicator) { return reportIndicatorsIds.indexOf(indicator.id) === -1; });
        };

        /**
         * Prepare the given Report object converting its date strings
         * to javascript Date objects.
         * @param report Report object
         * @returns prepared Report object
         */
        ReportService.helpers.preprocess = function(report) {
            report.evaluated_at = report.evaluated_at ? new Date(report.evaluated_at) : null;
            report.start_date = new Date(report.start_date);
            report.end_date = new Date(report.end_date);

            return report;
        };

        /**
         * Prepare the given Report object setting pivot values
         * for its users and indicators if undefined
         * @param report Report object
         * @returns prepared Report object
         */
        ReportService.helpers.postprocess = function(report) {
            report.users.forEach(function(user) {
                user.pivot = user.pivot || {};
                user.pivot.view_self = user.pivot.view_self || false;
                user.pivot.view_all = user.pivot.view_all || false;
            });

            report.indicators.forEach(function(indicator) {
                indicator.pivot = indicator.pivot || {};
                indicator.pivot.show_value = indicator.pivot.show_value || false;
                indicator.pivot.show_points = indicator.pivot.show_points || false;
            });

            return report;
        };

        /**
         * Get the data about indicators display properties used in the given report
         * for the purpose of creating results table.
         * @param report Report object
         * @returns {Array.<T>}
         */
        ReportService.helpers.getIndicatorsColumns = function(report) {

            return report.indicators.map(function(indicator) {
                var colspan = 0;

                if (indicator.pivot.show_value && indicator.pivot.show_points) {
                    colspan = 2;
                } else if (indicator.pivot.show_value || indicator.pivot.show_points) {
                    colspan = 1;
                }

                if (colspan > 0) {
                    return {
                        colspan: colspan,
                        name: indicator.name,
                        description: indicator.description
                    };
                }

            }).filter(function(indicator) {
                return indicator;
            });

        };

        /**
         * Get the data about indicators value and point columns
         * that should be displayed in the given report.
         * @param report Report object
         * @returns {Array.<T>}
         */
        ReportService.helpers.getIndicatorsPivotColumns = function(report) {

            return [].concat.apply([], report.indicators.map(function(indicator) {
                var tab = [];

                if (indicator.pivot.show_value) {
                    tab.push({
                        id: indicator.id,
                        type: 'value'
                    });
                }

                if (indicator.pivot.show_points) {
                    tab.push({
                        id: indicator.id,
                        type: 'points'
                    });
                }

                return tab;
            }));

        };

        /**
         * Transform the array of Result object into nested object
         * grouping results by users and then by indicators.
         * @param results Results objects array
         * @returns {{}} results grouped by users and indicators
         */
        ReportService.helpers.getUserResults = function(results) {
            var userResults = {};

            results.forEach(function(result) {
                if (userResults[result.user_id] === undefined) {
                    userResults[result.user_id] = {};
                }
                userResults[result.user_id][result.indicator_id] = {
                    value: result.value,
                    points: result.points
                }
            });

            return userResults;
        };

        /**
         * For each user calculate summary basing on currently selected
         * indicators, taking into account only those for which points
         * are displayed.
         * @param results Results objects array
         * @param report Report object
         * @returns {{}} Object of user summaries accessed using user's id
         */
        ReportService.helpers.getUsersStatistics = function(results, report) {
            var indicators = report.indicators.reduce(function(obj, indicator) {
                obj[indicator.id] = indicator;
                return obj;
            }, {});

            var usersStatistics = {};

            results.forEach(function(result) {
                if (usersStatistics[result.user_id] === undefined) {
                    usersStatistics[result.user_id] = {sum: 0};
                }
                usersStatistics[result.user_id].sum += indicators[result.indicator_id].pivot.show_points ? Number(result.points) : 0;
            });

            return usersStatistics;
        };

        /**
         * For each indicator calculate its minimum, maximum
         * and average value and points across all selected users.
         * @param results Results objects array
         * @returns {{}} Object of indicator summaries accessed using indicator's id
         */
        ReportService.helpers.getIndicatorsStatistics = function(results) {
            var indicatorsStatistics = {};

            results.forEach(function(result) {
                if (indicatorsStatistics[result.indicator_id] === undefined) {
                    indicatorsStatistics[result.indicator_id] = {
                        value: {
                            data: []
                        },
                        points: {
                            data: []
                        }
                    };
                }
                indicatorsStatistics[result.indicator_id].value.data.push(Number(result.value));
                indicatorsStatistics[result.indicator_id].points.data.push(Number(result.points));
            });

            angular.forEach(indicatorsStatistics, function(indicator) {
                indicator.value.min = Math.min.apply(null, indicator.value.data);
                indicator.value.max = Math.max.apply(null, indicator.value.data);
                indicator.value.avg = indicator.value.data.reduce(function(a, b) { return a + b; }) / indicator.value.data.length;
                indicator.points.min = Math.min.apply(null, indicator.points.data);
                indicator.points.max = Math.max.apply(null, indicator.points.data);
                indicator.points.avg = indicator.value.data.reduce(function(a, b) { return a + b; }) / indicator.points.data.length;
            });

            return indicatorsStatistics;
        };

        /**
         * Calculate minimum, maximum and average sum of points across all user
         * and currently displayed indicators.
         * @param usersStatistics Object of user summaries accessed using user's id
         * @returns {{min: number, max: number, avg: number}} global statistics object
         */
        ReportService.helpers.getGlobalStatistics = function(usersStatistics) {
            var sums = Object.keys(usersStatistics).map(function(id) { return usersStatistics[id].sum; });

            return {
                min: Math.min.apply(null, sums),
                max: Math.max.apply(null, sums),
                avg: sums.reduce(function(a, b) { return a + b; }) / sums.length
            };
        };

        /**
         * Check if any of report's critical parameters (influencing results)
         * is changed.
         * @param previous initial Report object
         * @param current possibly changed Report object
         * @returns {boolean} deep comparison result
         */
        ReportService.helpers.isReportChanged = function(previous, current) {

            var getId = function(obj) {
                return obj.id;
            };

            if ((new Date(previous.start_date)).getTime() !== (new Date (current.start_date)).getTime()) {
                return true;
            }

            if ((new Date(previous.end_date)).getTime() !== (new Date (current.end_date)).getTime()) {
                return true;
            }

            if (! angular.equals(previous.users.map(getId), current.users.map(getId))) {
                return true;
            }

            if (! angular.equals(previous.indicators.map(getId), current.indicators.map(getId))) {
                return true;
            }

            return false;

        };


        return ReportService;
    }

})();
