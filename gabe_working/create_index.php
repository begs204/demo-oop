<?php

// error_reporting(E_ALL); 
// ini_set("display_errors", 1);
	// $ind = new IndexFile();

	// $ind->site_url = 'http://www.cnn.com';
	// $ind->dashboard_id = 'ross_test4';
	// $ind->demo_url= 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/files/16tesssting20120522201621';
	// $ind->demo_dir ='/var/www/html/demos/files/16tesssting20120522201621'; 
	// $ind->createString();
	// $ind->saveIndex();

class IndexFile{
	var $site_url;
	var $dashboard_id;
	var $demo_url;
	var $string;
	var $demo_dir;

	function createString(){
		$this->string = '
		<!doctype html>
			<html>
			<style>
				html { overflow: hidden; }
				body { margin: 0; padding: 0; }
				#container { position: fixed; top: 0; bottom: 1px; width: 100%; }
				iframe {overflow:hidden; height:100%; width:100%;}
			</style>
			<body>

			<script type="text/javascript">
			window.Meebo||function(c){function p(){return["<",i,\' onload="var d=\',g,";d.getElementsByTagName(\'head\')[0].",
			j,"(d.",h,"(\'script\')).",k,"=\'//cim.meebo.com/cim?iv=",a.v,"&",q,"=",c[q],c[l]?
			"&"+l+"="+c[l]:"",c[e]?"&"+e+"="+c[e]:"","\'\"></",i,">"].join("")}var f=window,
			a=f.Meebo=f.Meebo||function(){(a._=a._||[]).push(arguments)},d=document,i="body",
			m=d[i],r;if(!m){r=arguments.callee;return setTimeout(function(){r(c)},100)}a.$=
			{0:+new Date};a.T=function(u){a.$[u]=new Date-a.$[0]};a.v=5;var j="appendChild",
			h="createElement",k="src",l="lang",q="network",e="domain",n=d[h]("div"),v=n[j](d[h]("m")),
			b=d[h]("iframe"),g="document",o,s=function(){a.T("load");a("load")};f.addEventListener?
			f.addEventListener("load",s,false):f.attachEvent("onload",s);n.style.display="none";
			m.insertBefore(n,m.firstChild).id="meebo";b.frameBorder="0";b.name=b.id="meebo-iframe";
			b.allowTransparency="true";v[j](b);try{b.contentWindow[g].open()}catch(w){c[e]=
			d[e];o="javascript:var d="+g+".open();d.domain=\'"+d.domain+"\';";b[k]=o+"void(0);"}try{var t=
			b.contentWindow[g];t.write(p());t.close()}catch(x){b[k]=o+\'d.write("\'+p().replace(/"/g,
			\'\\"\')+\'");d.close();\'}a.T(1)}({network:"'.$this->dashboard_id.'"});
			Meebo("unhide");
			</script>

			<div id="container">
			<iframe id ="mb_iframe" FRAMEBORDER="0" BORDER="0"> </iframe>
			<script  type="text/javascript" > document.getElementById("mb_iframe").src = "'.$this->site_url.'"</script>
			</div>
			<script type="text/javascript" src="'.$this->demo_url.'.js"></script>
			</body>
			</html>';
	}

	function saveIndex(){
		$this->createString();
		$file = fopen($this->demo_dir.'.html', 'w+');
		fwrite($file, $this->string);
		fclose($file);
		chmod($this->demo_dir.'.html',0777);
	}

}

?>