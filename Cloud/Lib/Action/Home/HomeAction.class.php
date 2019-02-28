<?php
header('Content-Type: text/html; charset=UTF-8');
class HomeAction extends Action{
	public function _initialize() {
		import('ORG.Util.Session');
		import('ORG.Util.Page');
        import('ORG.Util.Tree');
        import('ORG.Util.Dir');
        $webConf = D('Config')->getCfgByModule('WEBSITE');
        $config = json_decode($webConf['WEBSITE'], true);
        $data['Config'] = $config;
        if (empty($config['site_status'])){
        	header("Content-type: text/html; charset=utf-8"); 
	       	echo "网站升级关闭中!!";
	       	exit();
        }
        $this->assign('Web',$data);
	}
    /**
     * 前台统一分页 
     */
    public function HomePage($count, $pagesize) {
        $page = new Page($count, $pagesize);
        $page->setConfig("header", "条");
        $page->setConfig('theme', '共%totalRow%%header%&nbsp;%nowPage%/%totalPage%页&nbsp;%first%&nbsp;%upPage%&nbsp;%prePage%&nbsp;%linkPage%&nbsp;%nextPage%&nbsp;%downPage%&nbsp;%end%');
		return $page;
    }
	
}
?>