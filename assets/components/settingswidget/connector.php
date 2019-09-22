<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var modExtra $modExtra */
$modExtra = $modx->getService('settingswidget', 'settingsWidget', $modx->getOption('settingwidget_core_path', null,
        $modx->getOption('core_path') . 'components/settingswidget/') . 'model/settingswidget/'
);
$modx->lexicon->load('settingswidget:default');
// handle request
$corePath = $modx->getOption('settingswidget_core_path', null, $modx->getOption('core_path') . 'components/settingswidget/');
$path = $modx->getOption('processorsPath', $settingsWidget->config, $corePath . 'processors/');
$modx->getRequest();
/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));