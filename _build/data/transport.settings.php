<?php

$settings = array();

$tmp = array(
	/*'namespace' => array(
		'xtype' => 'textfield',
		'value' => 'minishop2',
		'area' => 'settingswidget_main',
	),*/
	'keys' => array(
		'xtype' => 'textarea',
		'value' => '',
		'area' => 'settingswidget_main' 
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'settingswidget_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
