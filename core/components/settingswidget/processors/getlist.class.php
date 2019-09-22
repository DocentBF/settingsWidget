<?php
include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/getlist.class.php';

class settingsWidgetGetList extends modSystemSettingsGetListProcessor {

    public function getData() {
        $q = $this->modx->newQuery($this->classKey);
        $key = $this->getProperty('key',false);
        $data = array();
        $criteria = $this->prepareCriteria();
        if (!empty($key)) {
            $criteria[] = array(
                $this->classKey.'.key:LIKE' => '%'.$key.'%',
                'OR:Entry.value:LIKE' => '%'.$key.'%',
                'OR:'.$this->classKey.'.value:LIKE' => '%'.$key.'%',
                'OR:Description.value:LIKE' => '%'.$key.'%',
            );
        }
        $namespace = $this->getProperty('namespace',false);
        if (!empty($namespace)) {
            /** @var modNamespace $namespaceObject */
            $namespaceObject = $this->modx->getObject('modNamespace', $namespace);
            if (!$namespaceObject) {
                $criteria[] = array('1 != 1');
            }
            $criteria[] = array('namespace' => $namespace);
        }

        $q->select(array(
            $this->modx->getSelectColumns('modSystemSetting','modSystemSetting'),
        ));
        $q->select(array(
            'name_trans' => 'Entry.value',
            'description_trans' => 'Description.value',
        ));
        $q->leftJoin('modLexiconEntry','Entry',"CONCAT('setting_',modSystemSetting.{$this->modx->escape('key')}) = Entry.name");
        $q->leftJoin('modLexiconEntry','Description',"CONCAT('setting_',modSystemSetting.{$this->modx->escape('key')},'_desc') = Description.name");
        $q->where($criteria);

        $count = $this->modx->getCount('modSystemSetting',$q);
        $sort = $this->getProperty('sort');
        if($sort)
            $q->sortby($sort, $this->getProperty('dir'));
        
        $limit = $this->getProperty('limit');
        $offset = $this->getProperty('start');
        if ((int) $limit > 0) {
            $q->limit((int) $limit, (int) $offset);
        }
        $q->prepare();

        $settingsResult = array(
            'count'=> $count,
            'collection'=> $this->modx->getCollection('modSystemSetting',$q)
        );

        $data['total'] = $settingsResult['count'];
        $data['results'] = $settingsResult['collection'];
        return $data;
    }

    public function prepareCriteria() {
    	$criteria = array();
        $keys = array_map('trim', explode(',', $this->getProperty('keys')));
        $keysStr = implode(',', array_map(array($this->modx, 'quote'),$keys));
    	if($keys) {
    		$criteria[] = array(
    			'key:IN' => $keys
    		);
            $this->setProperties(array(
                'sort' => 'FIELD(`modSystemSetting`.`key`, '.$keysStr.')',
                'dir' => ''
            ));
    	}

        return $criteria;
    }
}
return 'settingsWidgetGetList';