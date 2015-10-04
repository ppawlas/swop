<?php

namespace App\Repositories;

use App\Report;
use App\Result;


class ReportRepository
{

    public function getResultsTable(Report $report)
    {

        $rawResults = Result::whereHas('report', function($query) use($report) {
            $query->where('id', $report->id);
        })->get();

        $headers = array(
            'indicators' => array(),
            'components' => array()
        );

        foreach($report['indicators'] as $indicator) {
            array_push($headers['indicators'], array(
                'name' => $indicator->name,
                'description' => $indicator->description,
                'visible' => $indicator->pivot->show_value || $indicator->pivot->show_points,
                'colspan' => ($indicator->pivot->show_value && $indicator->pivot->show_points) ? 2 : 1
            ));

            array_push($headers['components'], array(
                'id' => $indicator->id,
                'type' => 'value',
                'displayName' => 'Wartość',
                'visible' => $indicator->pivot->show_value
            ));

            array_push($headers['components'], array(
                'id' => $indicator->id,
                'type' => 'points',
                'displayName' => 'Punkty',
                'visible' => $indicator->pivot->show_points
            ));
        }

        $results = [];
        $resultsByIndicator = [];

        foreach($rawResults as $result) {
            $userId = $result->user_id;

            if (! array_key_exists($userId, $results)) {
                $results[$userId] = array(
                    'displayName' => $result->user->name . ' ' . $result->user->surname,
                    'indicators' => [],
                    'sum' => 0
                );
            }

            $indicatorId = $result->indicator_id;
            $results[$userId]['indicators'][$indicatorId] = array(
                'value' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_value,
                    'data' => $result->value
                ),
                'points' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_points,
                    'data' => $result->points
                )
            );

            if($report->indicators()->find($indicatorId)->pivot->show_points) {
                $results[$userId]['sum'] += $result->points;
            }

            // results by indicator
            if (! array_key_exists($indicatorId, $resultsByIndicator)) {
                $resultsByIndicator[$indicatorId] = [];
            }
            $resultsByIndicator[$indicatorId][$userId] = $result;
        }
        $results = array_values($results);

        $statistics = array(
            'min' => array(
                'indicators' => [],
                'sum' => min(array_map(function($user) {
                    return $user['sum'];
                }, $results)),
            ),
            'max' => array(
                'indicators' => [],
                'sum' => max(array_map(function($user) {
                    return $user['sum'];
                }, $results)),
            ),
            'avg' => array(
                'indicators' => [],
                'sum' => array_sum(array_map(function($user) {
                    return $user['sum'];
                }, $results)) / count($results)
            )
        );

        foreach($resultsByIndicator as $indicatorId => $indicatorResults) {

            $statistics['min']['indicators'][$indicatorId] = array(
                'value' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_value,
                    'data' => min(array_map(function($result) {
                        return $result->value;
                    }, $indicatorResults))
                ),
                'points' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_points,
                    'data' => min(array_map(function($result) {
                        return $result->points;
                    }, $indicatorResults))
                )
            );

            $statistics['max']['indicators'][$indicatorId] = array(
                'value' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_value,
                    'data' => max(array_map(function($result) {
                        return $result->value;
                    }, $indicatorResults))
                ),
                'points' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_points,
                    'data' => max(array_map(function($result) {
                        return $result->points;
                    }, $indicatorResults))
                )
            );

            $statistics['avg']['indicators'][$indicatorId] = array(
                'value' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_value,
                    'data' => array_sum(array_map(function($result) {
                            return $result->value;
                        }, $indicatorResults)) / count($indicatorResults)
                ),
                'points' => array(
                    'visible' => $report->indicators()->find($indicatorId)->pivot->show_points,
                    'data' => array_sum(array_map(function($result) {
                            return $result->value;
                        }, $indicatorResults)) / count($indicatorResults)
                )
            );
        }

        return array(
            'headers' => $headers,
            'results' => $results,
            'statistics' => $statistics
        );
    }

}