<?php

defined('JPATH_BASE') or die;

jimport('joomla.plugin.plugin');


class plgSystemScripts_top extends JPlugin
{

    protected $scripts = array();

    public function onBeforeCompileHead()
    {

        if (JFactory::getApplication()->isSite()) {


            $exclude = $this->getScripts('exclude');

            $a_scripts = $this->getScripts('scripts');

            $document = JFactory::getDocument();

            $scripts = $document->_scripts;

            $new = array();

            foreach ($scripts as $k => $v) {
                if(!isset($new[$k])) {
                    if (!in_array($k, $exclude)) {
                        $new[$k] = $v;
                    }
                }
            }

            if(count($a_scripts) > 0 ) {
                $first_include = array();
                foreach ($a_scripts as $val) {
                    if (isset($new[$val])) {
                        $first_include[$val] = $new[$val];
                    } else {
                        $first_include[$val] = array("mime" => "text/javascript", "defer" => false, "async" => false);
                    }
                }
                $new = $first_include + $new;
            }

            $scripts = $new;

            $document->_scripts = $scripts;
        }

        return true;
    }

    function getScripts($param)
    {
        $p_scripts = trim($this->params->get($param));
        if (empty($p_scripts)) {
            return array();
        }
        $a_scripts = explode("\n", $p_scripts);
        foreach (array_keys($a_scripts) as $key) {
            $a_scripts[$key] = trim($a_scripts[$key]);
        }
        return $a_scripts;
    }
}