<?php
class settingsWidget
{
    /** @var modX $modx */
    public $modx;
    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('settingswidget_core_path', null,
            $this->modx->getOption('core_path') . 'components/settingswidget/'
        );
        $assetsUrl = $this->modx->getOption('settingswidget_assets_url', null,
            $this->modx->getOption('assets_url') . 'components/settingswidget/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';
        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl,
            'connectorUrl' => $connectorUrl,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'widgetsPath' => $corePath . 'elements/widgets/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
        ), $config);
        $this->modx->addPackage('settingswidget', $this->config['modelPath']);
        $this->modx->lexicon->load('settingswidget:default');
    }
}