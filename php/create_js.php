<?php

class JSFile{
	var $demo_dir;
	var $string;

	function saveJSFile(){
		$file = fopen($this->demo_dir.'.js', 'w+');
		fwrite($file, $this->string);
		fclose($file);
		chmod($this->demo_dir.'.js',0777);
	}
}
class JSButton{
	var $icon_url;
	var $icon_is_logo;
	var $img_url;
	var $img_w;
	var $img_ht;
	var $title;
	var $button_id;
	var $title_is_hidden;
	var $link_url;
	var $button_string;

	function createWidget(){
		if (isset($this->icon_url)){
			$icon_js='icon:"'.$this->icon_url.'",';
		}
		else{
			$icon_js = '';
		}
		if (isset($this->icon_is_logo) && $this->icon_is_logo == 1){
			$icon_is_logo_js='isLogo:true,';
		}
		else{
			$icon_is_logo_js = '';
		}
		if (isset($this->title) && $this->title_is_hidden != 1){
			$title_js='label:"'.$this->title.'",';
		}
		else{
			$title_js = '""';
		}
		$this->img_w = (string) $this->img_w + 4;
		$this->img_ht = (string) $this->img_ht + 4;
		$width_js = 'width:'.$this->img_w.',';
		$height_js = 'height'.$this->img_ht.',';

		$button_string= '
		Meebo(\'addButton\',{
		id:"mb_'.$this->button_id.'",
		type:"widget",
		'.$icon_js.'
		'.$title_js.'
		'.$width_js.'
		'.$height_js.',
				notResizable:true,
				noBorder:true,
		onCreate:function(widget,element){
				
					var mb_'.$this->button_id.'div=document.createElement(\'div\');
					var mb_'.$this->button_id.'img=document.createElement(\'img\');
					
					mb_aimg.src="'.$this->img_url.'";	
					mb_adiv.appendChild(mb_'.$this->button_id.'img);
					element.appendChild(mb_'.$this->button_id.'div);
				}	
		});';
	}

	function createLink(){
		if (isset($this->icon_url)){
			$icon_js='icon:"'.$this->icon_url.'",';
		}
		else{
			$icon_js = '';
		}
		if (isset($this->icon_is_logo) && $this->icon_is_logo == 1){
			$icon_is_logo_js='isLogo:true,';
		}
		else{
			$icon_is_logo_js = '';
		}
		if (isset($this->title) && $this->title_is_hidden != 1){
			$title_js='label:"'.$this->title.'",';
		}
		else{
			$title_js = '""';
		}
		$button_string='    
			Meebo(\'addButton\', {
			id:"mb_'.$this->button_id.'",
	        type: "action",
	        '.$icon_js.'
			'.$title_js.' 
	        onClick: function(){ document.location="'.$this->link_url.'"; } 
    		});';

	}	
}
?>