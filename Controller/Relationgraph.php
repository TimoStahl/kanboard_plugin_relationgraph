<?php

namespace Kanboard\Plugin\Relationgraph\Controller;

use Kanboard\Controller\BaseController;

class Relationgraph extends BaseController
{
    public function show()
    {
        $task = $this->getTask();

        $graph = $this->createGraph($task);

        $this->response->html(
            $this->helper->layout->task(
                'relationgraph:task/show',
                [
                    'title' => $task['title'],
                    'task' => $task,
                    'graph' => $graph,
                    'project' => $this->projectModel->getById($task['project_id'])
                ]
            )
        );
    }

    /**
     * @param $task
     * @return array
     * @throws \Exception
     */
    protected function createGraph($task)
    {
        $graph = [];
        $graph['tasks'] = [];
        $graph['edges'] = [];

        $this->traverseGraph($graph, $task);

        $graphData = [
            'nodes' => $graph['tasks'],
            'edges' => $graph['edges']
        ];

        return $graphData;
    }

    protected function traverseGraph(&$graph, $task)
    {
        if (!isset($graph['tasks'][$task['id']])) {
            $graph['tasks'][$task['id']] = [
                'id' => $task['id'],
                'title' => $task['title'],
                'active' => $task['is_active'],
                'project_id' => $task['project_id'],
                'project' => $task['project_name'],
                'score'=> $task['score'],
                'column' => $task['column_title'],
                'priority' => $task['priority'],
                'assignee' => $task['assignee_name'] ?: $task['assignee_username'],
                'color' => $this->colorModel->getColorProperties($task['color_id']),
                'url' => urlencode($this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])))
            ];
        }

        foreach ($this->taskLinkModel->getAllGroupedByLabel($task['id']) as $type => $links) {
            foreach ($links as $link) {
                if (!isset($graph['edges'][$task['id']][$link['task_id']]) &&
                    !isset($graph['edges'][$link['task_id']][$task['id']])) {
                    $graph['edges'][$task['id']][$link['task_id']] = $type;

                    $this->traverseGraph(
                        $graph,
                        $this->taskFinderModel->getDetails($link['task_id'])
                    );
                }
            }
        }
    }
}
