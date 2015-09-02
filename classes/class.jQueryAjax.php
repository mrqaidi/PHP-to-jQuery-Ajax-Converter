<?php
/**
 * Create jQuery Ajax javascript
 * https://github.com/rasclatt/PHP-to-jQuery-Ajax-Converter/
 * Licensed under the GNU GENERAL PUBLIC LICENSE
 * @author Rasclatt <rasclatt@me.com>
 * @version 0.0.1
 */
 
	class	jQueryAjax
		{
			public		$formname;
			public		$library_def;
			public		$libraries;
			public		$jQObj;
			
			protected	$url;
			protected	$jObject;
			protected	$AJAX;
			protected	$AjaxSettings;
			protected	$get_type;
			
			const		WP_COMPAT	=	'jQuery';
			const		SEND_POST	=	'post';
			const		SEND_GET	=	'get';
			const		GUESS		=	true;
			const		FORM_ACTION	=	true;
			const		jSON		=	'json';
			
			public	function __construct($jQObj = '$')
				{
					$this->jQObj			=	$jQObj;
					$this->AJAX['type']		=	"'post'";
					$this->library_def		=	'
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
					$this->formname			=	false;
					$this->jObject			=	array();
					$this->jObject['fTrig']	=	"";
					$this->jObject['eTrig']	=	"";
					$this->get_type			=	'val';
				}
				
			protected	function get_form_name($settings = false)
				{
					$find['id']			=	(!empty($settings['id']))? $settings['id']:false;
					$find['name']		=	(!empty($settings['name']))? $settings['name']:false;
					$find['class']		=	(!empty($settings['class']))? $settings['class']:false;
					$find['element']	=	(!empty($settings['element']))? $settings['element']:'form';
					
					$return			=	(empty($this->formname));
					
					foreach($find as $use => $value) {
							if($use == 'id' && $value != false) {
									$this->formname	=	$this->jQObj.'("#'.$value.'")';
									$script	=	'var	ThisObject	=	'.$this->formname.';';
									break;
								}
							elseif($use == 'name' && $value != false) {
									$this->formname	=	$this->jQObj.'("'.$find['element'].'[name=\''.$value.'\']")';
									$script	=	'var ThisObject	=	'.$this->formname.';';
									break;
								}
							elseif($use == 'class' && $value != false) {
									$this->formname	=	$this->jQObj.'(".'.$value.'")';
									$script	=	'var ThisObject	=	'.$this->formname.';';
									break;
								}
							else {
									$this->formname	=	$this->jQObj.'("'.$find['element'].'")';
									$script	=	'var ThisObject	=	'.$this->formname.';';
								}
						}
						
					return ($return)? $this->formname : PHP_EOL."\t\t\t\t".$script;
				}
				
			public	function UseForm($settings = false)
				{	
					$script	=	(empty($this->formname))? $this->get_form_name($settings) : 'var ThisObject	=	'.$this->jQObj.'(this);';
					
					$this->jObject['fconstruct']	=	$script.PHP_EOL.$this->jQObj.'.ajax(';
					$this->jObject['econstruct']	=	');';
					
					return $this;
				}
			
			public	function SendToUrl($url = false)
				{
					if($url === true)
						$this->url	=	'ThisObject.prop("action")';
					else
						$this->url	=	"'$url'";
						
					$this->SetAttr('url',$this->url);
					return $this;
				}
			
			public	function FormMethod($type = 'post')
				{
					if($type === true) {
							$type	=	(empty($_GET))? 'post':'get';
						}
						
					$this->SetAttr("type","'{$type}'");
					return $this;
				}
			
			protected	function MakeJSObjects($arr)
				{
					if(is_array($arr)) {			
							foreach($arr as $k => $v) {
										$return[$k]	=	$k.': '.$this->MakeJSObjects($v);
								}
						}
					else {
							$arr	=	(is_numeric($arr) || $arr === 'true' || $arr === 'false' || $arr === 'data' || $arr === 'ThisObject')? $arr: "'$arr'";
							$return	=	(strpos($arr,'{') !== false && strpos($arr,'}') !== false || strpos($arr,'ThisObject.') || strpos($arr,$this->jQObj))? trim($arr,"'") : $arr;
							
							$return	=	str_replace("''","'",$return);
						}
					
					return (is_array($return))? '{ '.PHP_EOL."\t".implode(",\t".PHP_EOL."\t",$return).PHP_EOL.' }' : $return;
				}
			
			public	function SendFormData($data = false)
				{
					$use	=	(is_array($data))? $this->MakeJSObjects($data) : 'ThisObject.serialize()';
					$this->SetAttr("data",$use);
					return $this;
				}
			
			public	function SetAttr($key = false, $value = false)
				{
					$this->AJAX[$key]	=	$value;
					return $this;
				}
			
			public	function SendDataAs($type = 'json')
				{
					$this->SetAttr("dataType", $type);
					return $this;
				}
			
			public	function AddLibraries($libs = false)
				{
					$this->uselibs		=	true;
					
					if(is_array($libs) && !empty($libs)) {
							foreach($libs as $link) {
									$this->libraries[]	=	'<script type="text/javascript" src="'.$link.'"></script>';
								}
						}
						
					return $this;
				}
					
			public	function OnSuccess($settings = false)
				{
					$generate	=	(!empty($settings['generate']))? $settings['generate'] : false;
					$postto		=	(!empty($settings['populate']))? $settings['populate'] : false;
					$pstScript	=	(!empty($settings['script']['post']))? $settings['script']['post'] : false;
					$preScript	=	(!empty($settings['script']['pre']))? $settings['script']['pre'] : false;
					$silent		=	(!empty($settings['silent']))? $settings['silent'] : false;
					
					$success['func_f']	=	'function(response) {';
					
					if(is_array($postto)) {
							
							if(isset($postto['id'])) 
								$success['func_obj']	=	"\t\t\t".$this->jQObj."('#{$postto['id']}')";
							elseif(isset($postto['class']))
								$success['func_obj']	=	"\t\t\t".$this->jQObj."('.{$postto['class']}')";
						}
					
					$success['script_pre']		=	$preScript;
					
					if($generate == 'html')
						$success['func_obj']	.=	".html(response);";
					elseif($generate == 'val')
						$success['func_obj']	.=	".val(response);";
					
					if(!$generate && !$postto && !$silent) {
							$success['func_obj']	.=	PHP_EOL."\t\t\t\t".'
							if(response) {
								var	ObjVals	=	response;
								'.$this->jQObj.'.each(ObjVals,function(key,value) {
								try {
									ThisObject.find("input[name=\'"+key+"\']").val(value);
								}
								catch(Exception) {
									alert("This action failed. Check how/what your data is sent.");
								}
								});
							}'.PHP_EOL;
						}

					$success['script_post']	=	$pstScript;
					$success['func_e']		=	PHP_EOL."\t\t\t\t},".PHP_EOL."\t\t\t\t".'error: function(e) {
								alert("Error: Check your console for problen. "+toString.e);
							}
					';
					$this->AJAX['success']	=	implode(PHP_EOL,$success);
					
					return $this;
				}
			
			public	function ActivateAjax($settings = false)
				{
					$action			=	(!empty($settings['on']))? $settings['on'] : "submit";
					$find['id']		=	(!empty($settings['id']))? $settings['id']:false;
					$find['name']	=	(!empty($settings['name']))? $settings['name']:false;
					$find['class']	=	(!empty($settings['class']))? $settings['class']:false;
					
					$this->formname			=	$this->get_form_name($find);
					$this->UseForm($find);
					$this->jObject['fTrig']	=	$this->formname.".{$action}(function(e) {".PHP_EOL."\t\t\t\t";
					$this->jObject['eTrig']	=	($action == 'submit')? PHP_EOL."\t\t".'e.preventDefault();'.PHP_EOL:"";
					$this->jObject['eTrig']	.=	"\t\t".'});';
					return $this;
				}
			
			public	function GetSettings()
				{
					$this->AjaxSettings['ajax']	=	$this->AJAX;
					return	$this->AjaxSettings;
				}
			
			public	function Construct()
				{
					ob_start();
					echo $this->jObject['fTrig'].PHP_EOL;
					echo $this->jObject['fconstruct'].$this->MakeJSObjects($this->AJAX);
					echo $this->jObject['econstruct'];
					echo $this->jObject['eTrig'].PHP_EOL;
					$data	=	ob_get_contents();
					ob_end_clean();
					
					return $data;
				}
			
			protected	function make_script($value = false,$array = array())
				{
					if(empty($value))
						return false;
						
					$specific['class']	=	'.';
					$specific['id']		=	'#';
					
					$form['input']		=	'input';
					$form['select']		=	'select';
					$form['textarea']	=	'textarea';
					$form['password']	=	'password';
					
					$parent['div']		=	'div';
					$parent['span']		=	'span';
					
					$classfind			=	"";
					$last				=	end($array);
					$useprop			=	(strpos($this->get_type,'(') !== false || strpos($this->get_type,')') !== false)? $this->get_type : $this->get_type.'(';
					if(isset($specific[$value])) {
							$rep	=	preg_replace('/[0-9A-Za-z\_]/',"_",$last);
							return	"'{$specific[$value]}{$last}').".$useprop;
						}
					elseif(isset($parent[$value])) {
							if(in_array("class",$array)) {
									$classfind	=	".".$last;
								}
								
							return '"'.$parent[$value].$classfind.'").'.$useprop;
						}
				}
			
			public	function get_value_of($settings = false,$get_type = 'val')
				{
					$this->get_type	=	rtrim($get_type,")");
					$type			=	(!empty($settings['type']))? $settings['type'] : 'input';
					$name			=	(!empty($settings['name']))? $settings['name'] : false;
					$find			=	(!empty($settings['find']))? $settings['find'] : false;
					
					if($find != false) {
							$instructions	=	explode('->',$find);
							
							
							if(empty($instructions))
								return 'false';
							
							$thisObj[]	=	$this->jQObj.'(';
							foreach($instructions as $value) {
									$thisObj[]	=	$this->make_script($value,$instructions);
								}
							$thisObj[]	=	')';
						
							return implode("",$thisObj);
						}
					
					return "ThisObject.find('{$settings[type]}[name={$settings[name]}]').{$get_type}()";
				}
		}
?>
