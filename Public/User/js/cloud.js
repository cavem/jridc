var vmstates='{';
vmstates+="running:{'image':'/Public/User/images/running.png','title':'运行'},";
vmstates+="halted:{'image':'/Public/User/images/halted.png','title':'停止'},";
vmstates+="creating:{'image':'/Public/User/images/loading.gif','title':'创建中'},";
vmstates+="error:{'image':'/Public/User/images/error.png','title':'创建失败'},";
vmstates+="unknow:{'image':'/Public/User/images/except.png','title':'异常'},";
vmstates+="suppending:{'image':'/Public/User/images/starting.png','title':'操作中'},";
vmstates+="nocloud:{'image':'/Public/User/images/except.png','title':'虚拟机未找到'},";
vmstates+="loading:{'image':'/Public/User/images/loading.gif','title':'加载中'}";
vmstates+='}';
var vmstate=eval("(" + vmstates + ")");
function get_vm_status() {
    var vms = $("input[name='vmid[]']");
    if (vms.length == 0) {
        if (vm_interval) {
            clearInterval(vm_interval);
        }
        return;
    }
    for (var i=0; i<vms.length; i++) {
        vmid = vms[i].value;
        $.get(getvmstatusurl,{'act':'vmnetwork','id':vmid},function(network){
				if(network['statusinfo']=='正常'){
					if(network['cloudstatus']==true){
						$("#vm_ip_"+network['id']).html(network['value']);
						
					}else{
						$("#vm_ip_"+network['id']).html(network['info']);
					}
				}else if(vminfo['statusinfo']=='配置中'){
					
					
				}else if(vminfo['statusinfo']=='开通失败'){
					
					
				}else{
					$("#vm_ip_"+network['id']).html(network['info']);
				}
        },'json');
    }
}