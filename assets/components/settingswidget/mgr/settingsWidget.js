var SettingsWidget = function (config) {
    config = config || {};
    SettingsWidget.superclass.constructor.call(this, config);
};
Ext.extend(SettingsWidget, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('settingsWidget', SettingsWidget);

SettingsWidget = new SettingsWidget();