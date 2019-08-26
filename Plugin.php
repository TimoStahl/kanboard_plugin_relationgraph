<?php

namespace Kanboard\Plugin\Relationgraph;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        $this->route->addRoute('/plugin/relation_graph/:task_id', 'relationgraph', 'show', 'relationgraph');

        $this->hook->on('template:layout:js', array('template' => 'plugins/Relationgraph/Asset/Javascript/vis/vis.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/Relationgraph/Asset/Javascript/GraphBuilder.js'));
        $this->hook->on('template:layout:css', array('template' => 'plugins/Relationgraph/Asset/Javascript/vis/vis.css'));

        $this->template->hook->attach('template:task:sidebar:information', 'relationgraph:task/sidebar');
    }

    public function getPluginName()
    {
        return 'Relationgraph';
    }

    public function getPluginAuthor()
    {
        return 'BlueTeck, Xavier Vidal';
    }

    public function getPluginVersion()
    {
        return '0.3.1';
    }

    public function getPluginDescription()
    {
        return t('Show relations between tasks using a graph');
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/BlueTeck/kanboard_plugin_relationgraph';
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.10';
    }
}
