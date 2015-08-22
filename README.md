# PHP-to-jQuery-Ajax-Converter
This php class will assemble your basic jQuery Ajax based on input. Beta

````php

	include(__DIR__.'/classes/class.nUberJTools.php');

	//nUberJTools::Initialize(nUberJTools::WP_COMPAT);
	$ajax	=	nUberJTools::jQueryAjax();
	$form['on']	=	'change';
	$form['id']	=	'tester';
	
	$ajax->ActivateAjax($form);
	$ajax->SendToUrl(jQueryAjax::FORM_ACTION);
	$ajax->FormMethod(jQueryAjax::SEND_POST);
	
	$send["butter"]			=	$ajax->get_value_of(array("find"=>"class->get_some_text"),'prop("id")');
	$send["subutter"]		=	$ajax->get_value_of(array("type"=>"input","name"=>"butter"));
	$send["that_class"]		=	$ajax->get_value_of(array("type"=>"select","name"=>"this_class"));
	$send["custom_key"]		=	"Some random, hardcoded value";
	$send["js_cust"]		=	array("fuzz"=>$ajax->jQObj.'(\'input[name=getit]\').val()');
	$send["html"]			=	$ajax->get_value_of(array("find"=>"class->get_some_text"),'html');
	$send["data_detect"]	=	$ajax->get_value_of(array("find"=>"id->cookie"),'data("test")');
	$send["paste"]			=	$ajax->get_value_of(array("type"=>"input","name"=>"paste"));
	$ajax->SendFormData($send);
	$ajax->OnSuccess(array("populate"=>array("id"=>"testback"),"generate"=>"html"));
?>
<?php echo $ajax->library_def; ?>
<script>
<?php echo nUberJTools::DocumentReady($ajax->Construct()); ?>
</script>
````
See the example page for working script. The above will output:

````html
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script><script>
$(document).ready(function() { 
$("#tester").change(function(e) {
				
var ThisObject	=	$(this);
$.ajax({ 
	type: 'post',	
	url: ThisObject.prop("action"),	
	data: { 
	butter: $('.get_some_text').prop("id"),	
	subutter: ThisObject.find('input[name=butter]').val(),	
	that_class: ThisObject.find('select[name=this_class]').val(),	
	custom_key: 'Some random, hardcoded value',	
	js_cust: { 
	fuzz: $('input[name=getit]').val()
 },	
	html: $('.get_some_text').html(),	
	data_detect: $('#cookie').data("test"),	
	paste: ThisObject.find('input[name=paste]').val()
 },	
	success: function(response) {
			$('#testback').html(response);



				},
				error: function(e) {
								alert("Error: Check your console for problen. "+toString.e);
							}
					
 });		});
					}); 
</script>
````
