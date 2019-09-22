SettingsWidget.grid = function(config) {
    config = config || {};

    this.cm = new Ext.grid.ColumnModel({
        columns: [{
            header: _('name')
            ,dataIndex: 'name_trans'
            ,sortable: true
            ,editable: false
            ,width: 80
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,sortable: true
            ,editable: true
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
        }]
        ,getCellEditor: function(colIndex, rowIndex) {
            var field = this.getDataIndex(colIndex);
            if (field == 'value') {
                var rec = config.store.getAt(rowIndex);
                var xt = {xtype: 'textfield'};
                if (rec) {
                    xt.xtype = rec.get('xtype');
                    if (xt == 'text-password') {
                        xt.xtype = 'textfield';
                        xt.inputType = 'password';
                    }
                }
                var o = MODx.load(xt);
                return new Ext.grid.GridEditor(o);
            }
            return Ext.grid.ColumnModel.prototype.getCellEditor.call(this, colIndex, rowIndex);
        }
    });

    Ext.applyIf(config,{
        title: _('recent_docs')
        ,url: SettingsWidget.config.connector_url
        ,baseParams: {
            action: 'getlist',
            keys: config.keys,
        }
        ,autosave: true
        ,save_action: 'update'
        ,pageSize: 10
        ,cm: this.cm
        ,fields: ['key','name','value','description','xtype','namespace','area','area_text','editedon','oldkey','menu','name_trans','description_trans']
        ,paging: true
        ,listeners: {
            afteredit: this.refresh
            ,afterrender: this.onAfterRender
            ,scope: this
        }
    });
    SettingsWidget.grid.superclass.constructor.call(this,config);
};
Ext.extend(SettingsWidget.grid,MODx.grid.Grid,{
    preview: function() {
        window.open(MODx.config.base_url+'?id='+this.menu.record.id);
    }
    
    ,refresh: function() {
        var tree = Ext.getCmp('modx-resource-tree');
        if (tree && tree.rendered) {
            tree.refresh();
        }
    }

    ,onAfterRender: function() {
        var cnt = Ext.getCmp('modx-content')
            ,parent = Ext.get('settingswidget-grid');

        if (cnt && parent) {
            cnt.on('afterlayout', function(elem, layout) {
                var width = parent.getWidth();
                if (width > 500) {
                    this.setWidth(width);
                }
            }, this);
        }
    }

    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        v = Ext.util.Format.htmlEncode(v);
        var f;
        if (r.xtype == 'combo-boolean' || r.xtype == 'modx-combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'datefield') {
            f = Ext.util.Format.dateRenderer(MODx.config.manager_date_format);
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'text-password' || r.xtype == 'modx-text-password') {
            f = MODx.grid.Grid.prototype.rendPassword;
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype.substr(0,5) == 'combo' || r.xtype.substr(0,10) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                var o = Ext.ComponentMgr.create({xtype: r.xtype || 'textfield'});
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            if (ed.store && !ed.store.isLoaded && ed.config.mode != 'local') {
                ed.store.load();
                ed.store.isLoaded = true;
            }
            f = Ext.util.Format.comboRenderer(ed.field,v);
            return f(v,md,rec,ri,ci,s,g);
        }
        return v;
    }
});
Ext.reg('settingswidget-grid',SettingsWidget.grid);