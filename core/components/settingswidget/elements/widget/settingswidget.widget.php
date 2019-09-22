<?php
$corePath = $modx->getOption('settingswidget_core_path',null,$modx->getOption('core_path'). 'components/settingswidget/');
require_once $corePath . '/model/settingswidget/settingswidget.class.php';

class settingsWidgetWidget extends modDashboardWidgetInterface {
    public function render() {
        $keys = preg_replace('/\'|\"|\”|\“/','',$this->modx->getOption('settingswidget_keys'));
        $keys = trim($keys);
        
        $sw = new settingsWidget($this->modx);

        if(empty($keys))
            return '<p>[[%emptykeys]]</p>';
    	
        $this->controller->addJavascript($sw->config['jsUrl'].'mgr/settingsWidget.js');
        $this->controller->addJavascript($sw->config['jsUrl'].'mgr/widgets/settingswidget.grid.js');
        $this->controller->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
        	SettingsWidget.config = ' . json_encode($sw->config) . ';
        	SettingsWidget.config.connector_url = "' . $sw->config['connectorUrl'] . '";
		    MODx.load({
		        xtype: "settingswidget-grid",
		        renderTo: "settingswidget-grid",
		        keys: "'.$keys.'"
		    });
		});</script>');

        return '<p>[[%message]]</p>
		<br />
		<div id="settingswidget-grid"></div>';
    }
}

return 'settingsWidgetWidget';