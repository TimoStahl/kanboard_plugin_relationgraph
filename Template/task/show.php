<div class="task-show-title color-<?= $task['color_id'] ?>">
    <h2><?= $this->text->e($task['title']) ?></h2>
</div>

<div class="page-header">
    <h2><?= t('Relation graph') ?></h2>
</div>

<style type="text/css">
    #mynetwork {width: 900px;height: 700px;border: 1px solid lightgray;}
</style>

<div id="mynetwork"></div>

<div id="graph-nodes" style="display:none">
    <?php $items = []; ?>
    <?php foreach ($graph['nodes'] as $node) : ?>
        <?php
        $titleItems = [];

        if ($node['project_id'] != $task['project_id']) {
            $titleItems[] = t('Project').': ' . $node['project'];
        }

        if ($node['score'] > 0) {
            $titleItems[] = t('Score').': ' . $node['score'];
        }

        if ($node['assignee'] != '') {
            $titleItems[] = t('Assignee').': ' . $node['assignee'];
        }

        $titleItems[] = t('Priority').': ' . $node['priority'];
        $titleItems[] = t('Column').': ' . $node['column'];

        $items[] = [
            'id' => $node['id'],
            'label' => '#' . $node['id'] . ' ' . $node['title'],
            'color' => $node['color'],
            'shape' => 'box',
            'size' => '20',
            'shapeProperties' => $node['active'] ? array('borderDashes' => array()) : array('borderDashes' => array(5, 5)),
            'font' => array('color' => $node['active'] ? 'black' : 'gray'),
            'scaling' => [
                'min' => 30,
                'max' => 30
            ],
            'shadow' => 'true',
            'mass' => 2,
            'url' => $node['url'],
            'title' => join('<br>', $titleItems)
        ];
        ?>
    <?php endforeach ?>
    <?php echo json_encode($items) ?>
</div>

<div id="graph-edges" style="display:none">
    <?php $items = [] ?>
    <?php foreach ($graph['edges'] as $task => $links) : ?>
        <?php foreach ($links as $edge => $type) : ?>
            <?php
            $items[] = [
                'from' => $task,
                'to' => $edge,
                'label' => t($type),
                'length' => 200,
                'font' => ['align' => 'top'],
                'arrows' => 'to'
            ];
            ?>
        <?php endforeach ?>
    <?php endforeach ?>
    <?php echo json_encode($items) ?>
</div>