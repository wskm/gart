
function table_hover(classname)
{
	$("." + classname + " tr:not(.sp)").mouseover(function(){$(this).addClass("over");})
	.mouseout(function(){$(this).removeClass("over");})
}

function table_evenbg(classname,isodd)
{
	var ename=typeof isodd =='undefined' ?'even':'odd';
	$("." + classname + " tr:"+ ename +":not(.sp):not(.nobg)").addClass("even");
}

function tourl(tourl){
	location.href=tourl;
}

function displayMenu(id,type,ofs){
	var obj=$('#'+id);
		
	if(typeof type == 'undefined' || type != 'click'){
		obj.hover(
		function () {
			var menu=$('#'+id+'_menu');
			setPosition(id,id+'_menu',ofs);
			menu.show();
		},
		function () {
			var menu=$('#'+id+'_menu');
			menu.hover(
			function () {
				menu.show();
			},
			function () {
				menu.hide();
			}
			);
			menu.hide();
		}
		);
	}else if(type == 'click'){
		obj.click(function(){
			var menu=$('#'+id+'_menu');
			setPosition(id,id+'_menu',ofs);
			menu.show();
		});
	}
}

function setPosition(eid,menuid,ofs){
	var obj=$('#'+eid);
	var menu=$('#'+menuid);
	var lt=obj.offset();
	if(typeof ofs != 'object')ofs=[];
	var wh=$(window).height();
	var wsh=$(window).scrollTop();
	var zh=obj.outerHeight(true);
	
	if( (lt.top > wh/2+120)  &&  (wsh < (wh/2+menu.outerHeight(true)+120)) ){
		zh=-menu.outerHeight(true);
	}
	var zleft=ztop=0;
	if(typeof ofs.left == 'number')zleft=ofs.left;
	if(typeof ofs.top == 'number')ztop=ofs.top;
	
	menu.css('position','absolute');
	menu.css('left',lt.left + zleft);
	menu.css('top',lt.top + zh + ztop);
}