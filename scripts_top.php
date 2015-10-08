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

            if(count($exclude) > 0 && count($a_scripts) > 0){
                foreach ($exclude as $ve) {
                    if(isset($a_scripts[$ve])) unset($a_scripts[$ve]);
                }
            }

            if(count($exclude) > 0 || count($a_scripts) > 0) {


                $document = JFactory::getDocument();
                $scripts = $document->_scripts;

                $new = array();

                foreach ($scripts as $k => $v) {

                    if (in_array($k, $exclude)) {
                        unset($scripts[$k]);
                    }

                    if (in_array($k, $a_scripts)) {
                        $new[$k] = $v;
                        unset($scripts[$k]);
                    }

                }


                $impa = array();
                foreach ($a_scripts as $val) {
                    if (!empty($new) && isset($new[$val])) {
                        $impa[$val] = $new[$val];
                    } else {
                        $impa[$val] = array("mime"=>"text/javascript","defer"=>false,"async"=>false);
                    }
                }
                $scripts = $impa + $scripts;


                $document->_scripts = $scripts;

            }
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