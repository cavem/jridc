<?php
class TagLibCloud extends TagLib {
 	 protected $tags = array(
 	 'article' => array('attr' => 'field,limit,order,where,key,mod,result,typeid','close'=>1,'level'=>3),
 	 'sql'=>array('attr' => 'sql,key,mod,result','close'=>1,'level'=>3),
 	 'page'=>array('attr' => 'field,limit,order,where,key,mod,result,typeid','close'=>1,'level'=>3)
 	 );
 	 public function _page($attr,$content){
 	 	$tag=$this->parseXmlAttr($attr,'page');
	 	$result = !empty($tag['result'])?$tag['result']:'data'; //定义数据查询的结果存放变量
	 	$key = !empty($tag['key'])?$tag['key']:'i';
	 	$mod = isset($tag['mod'])?$tag['mod']:'2';
	 	$sql = "M('page')->";
	    $sql .= ($tag['field'])?"field('{$tag['field']}')->":'';
	    $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
	    $sql .= ($tag['where'])?"where(\"{$tag['where']}\")->":'';   //被重新处理过了
	    $sql .= ($tag['limit'])?"limit(\"{$tag['limit']}\")->":''; 
	    $sql .= "select()";   
	    //下面拼接输出语句
	   $parsestr = '<?php $_result='.$sql.';?>';
	   $parsestr .='<?php';
	   $parsestr .='if($_result): $'.$key.'=0;';
	   //拼接当前 类型的名称
	   $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
	   $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
	   $parsestr .= $content;//解析在article标签中的内容
	   $parsestr .= '<?php endforeach; endif;?>';
	   return $parsestr;
 	 }
	 public function _article($attr,$content){
	 	$tag=$this->parseXmlAttr($attr,'article');
	 	$result = !empty($tag['result'])?$tag['result']:'data'; //定义数据查询的结果存放变量
	 	$key = !empty($tag['key'])?$tag['key']:'i';
	 	$mod = isset($tag['mod'])?$tag['mod']:'2';
	 	if (!empty($tag['field']))$tag['field']=$tag['field'].',id,cid';
	 	if (!empty($tag['typeid'])){
	 		 $typedata=M('article_category')->where(array('status'=>1))->select();
	 		 $typearr=explode(',', $tag['typeid']);
	 		 $cids=$tag['typeid'];
	 		 foreach ($typearr as $k=>$v){
	 		 	$cids=$cids.",".GetSonIds($typedata,$v,true);	
	 		 }
	 	}
	 	if (!empty($cids) && !empty($tag['where'])){
	 	 	$tag['where']="cid in(".$cids.") and ".$tag['where'];
	 	}
 	 	if (!empty($cids) && empty($tag['where'])){
	 	 	$tag['where']="cid in(".$cids.")";
	 	}
	    $sql = "M('article')->";
	    $sql .= ($tag['field'])?"field('{$tag['field']}')->":'';
	    $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
	    $sql .= ($tag['where'])?"where(\"{$tag['where']}\")->":'';   //被重新处理过了
	    $sql .= ($tag['limit'])?"limit(\"{$tag['limit']}\")->":''; 
	    $sql .= "select()";   
	   //下面拼接输出语句
	   $parsestr = '<?php $_result='.$sql.';';
	   $parsestr .='$cate=M("article_category");';
	   $parsestr .='foreach($_result as $key=>$v):';
	   $parsestr .='$_result[$key][catename]=$cate->where("id=".$v[cid])->getField("title");?>';
	   $parsestr .='<?php endforeach;?>';
	   $parsestr .='<?php';
	   $parsestr .='if($_result): $'.$key.'=0;';
	   //拼接当前 类型的名称
	   $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
	   $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
	   $parsestr .= $content;//解析在article标签中的内容
	   $parsestr .= '<?php endforeach; endif;?>';
	   return $parsestr;
	 }
	 public function _sql($attr,$content){
	 	 $tag=$this->parseXmlAttr($attr,'data');
	 	 $key = !empty($tag['key'])?$tag['key']:'i';
	 	 $mod = isset($tag['mod'])?$tag['mod']:'2';
	 	 $result = !empty($tag['result'])?$tag['result']:'data'; //定义数据查询的结果存放变量
	 	 $parsestr = '<?php $mode=M(); ?>';
	 	 $parsestr .= '<?php ';
	 	 $parsestr .=' $_results=$mode->query("'.$tag['sql'].'");';
		 $parsestr .=' ?>';
		 $parsestr .='<?php';
	     $parsestr .='if($_results): $'.$key.'=0;';
	     $parsestr .= 'foreach($_results as $key=>$'.$result.'):';
	     $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
	     $parsestr .= $content;//解析在article标签中的内容
	     $parsestr .= '<?php endforeach; endif;?>';
		 return $parsestr;
	 }
}