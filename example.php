<?php
	if(!empty($_POST)) {
		ob_start();
		?>
		<style>
		div.ajax_wrap	{
			 display: inline-block;
			 text-shadow: 1px 1px 2px #000;
		}
		h3	{
			font-size: 20px;
			color: #FFF;
			margin: 5px 0 0 0;
		}
		div.typer	{
			padding: 5px 10px;
			background-color: #111;
			color: #FFF;
			float: left;
			margin-bottom: 5px;
			box-shadow: none;
			box-shadow: 1px 1px 10px #000;
			border: 3px solid #CCC;
		}
		.allkeys div.typer	{
			display: table-row;
			color: #FFF;
		}
		</style>
		<?php $bkg	=	'rgb('.rand(0,255).','.rand(0,255).','.rand(0,255).')'; ?>
		<div style="display: inline-block;" class="ajax_wrap">
			<div style="display: inline-block; width: 100%;">
				<div class="typer">
					<p><?php echo base64_encode(md5(__DIR__.date("HisYmd").rand().uniqid().$_POST['butter'])); ?></p>
				</div>
			</div>
			<div class="allkeys">
				<h3>All $_POST key/value pairs:</h3>
				<?php
				foreach($_POST as $key => $vals) {
					$vals	=	(is_array($vals))? implode("",$vals):$vals;
					echo "<div style=\"display: inline-block; width: 100%;\"><div class=\"typer\">".$key." => ".$vals."</div></div>";	
				} ?>
			</div>
		</div>
		<?php
		
		$data = ob_get_contents();
		ob_end_clean();
		?>
		<div style="background-color: <?php echo $bkg; ?>; border: <?php echo $px; ?> solid #FFF;padding: 20px; display: inline-block;">
			<?php echo $data; ?>
		</div>
		<?php
		exit;	
	}

	include(__DIR__.'/classes/class.nUberJTools.php');
	
	/* @method ::Initialize
	** This method allows for globalizing how your jQuery will run.
	** For instance, in WordPress, it may be necessary to use the "nUberJTools::WP_COMPAT" setting
	*/
	//nUberJTools::Initialize(nUberJTools::WP_COMPAT);
	
	/* @method ::Ajax
	** This method initializes the AJAX class/app
	*/
	$ajax	=	nUberJTools::jQueryAjax();
	
	/* @param array ["on"]: This is the trigger that activates the ajax.
	** This setting can have keyup, keypress, submit, change etc.
	**
	** @param array ["id"]: This is how to identify the element (form,or other)
	** If you use "id", your element must have the same "id" name: <form id="tester">...etc
	** You can use "class" to identify a specific element from a set of elements:
	** <div class="myclass">test1</div>
	** <div class="myclass">test2</div>
	*/
	$form['on']	=	'change';
	$form['id']	=	'tester';
	
	/* @method ::ActivateAjax
	** This method is how Ajax knows what to do. It implements the settings established above
	** In this instance, the setting are meant to activate the form labeled as "tester" using the id as the selector:
	** <form id="tester">
	** In this instance the ajax will fire when when user presses and releases a key on the keyboard inside the form
	*/
	$ajax->ActivateAjax($form);
	
	/* @method ::SendToUrl
	** This method assigns where the browser will send the ajax data to
	** In this instance, the url is set in the "action" attribute of the form: <form action="/example.php">
	** You can also specify the location like: ->SendToUrl('/set/anew/form/location.php')
	*/ 
	$ajax->SendToUrl(jQueryAjax::FORM_ACTION);
				
	/* @method ::FormMethod
	** This method assigns how to send the data to the url
	** The default is sending via a POST. You send using GET like so: ->FormMethod(jQueryAjax::SEND_GET)
	*/
	$ajax->FormMethod(jQueryAjax::SEND_POST);
	
	/* @method ::SendDataAs
	** You are able add add a sendData attribute if necessary
	** "jQueryAjax::jSON" is currently the only preset
	*/
	// $ajax->SendDataAs(jQueryAjax::jSON);
	
	/* @method ::SendFormData
	** This method actually what identifies what data to send. The default is to just send the contents of the form.
	** To create a set of specific data points to send, you can add an array and the method will convert the php array to a javascript
	** object-formatted array
	*///
	
	// Try to find an element with the css class name "get_some_text". The second argument tries to get the "id" value of the element
	$send["butter"]			=	$ajax->get_value_of(array("find"=>"class->get_some_text"),'prop("id")');
	// Notice the form input named "butter" which is typable is being overwritten by the above array with key name "butter"
	// This makes a new key called "subutter" and takes the value from "butter"
	$send["subutter"]		=	$ajax->get_value_of(array("type"=>"input","name"=>"butter"));
	// Creates a new POST key name but tries to get the value from the dropdown menu
	$send["that_class"]		=	$ajax->get_value_of(array("type"=>"select","name"=>"this_class"));
	// This is just how to hardcode a key/value pair
	$send["custom_key"]		=	"Some random, hardcoded value";
	// This creates a new key value pair named "js_cust", but the value that will be sent is an array.
	// This particular sub-array value is trying to get the data from an form input that is not inside the current form.
	$send["js_cust"]		=	array("fuzz"=>$ajax->jQObj.'(\'input[name=getit]\').val()');
	// This will fetch the value of something inside an html element
	$send["html"]			=	$ajax->get_value_of(array("find"=>"class->get_some_text"),'html');
	// This will retrieve the info inside the "data-test" attribute
	$send["data_detect"]	=	$ajax->get_value_of(array("find"=>"id->cookie"),'data("test")');
	// This will retrieve the info inside the "data-test" attribute
	$send["paste"]			=	$ajax->get_value_of(array("type"=>"input","name"=>"paste"));
	// Send the data fields
	$ajax->SendFormData($send);
	
	/* @method ::OnSuccess
	** ["populate"] tells the class to fill the element with the id="testback" with the ajax resonse
	** ["generate"] tells the response to populate the element with html
	** If left empty (no arguments), it will try to repopulate your form. Depending on above
	** settings, this action may fail to produce anything except an error
	*/
	$ajax->OnSuccess(array("populate"=>array("id"=>"testback"),"generate"=>"html"));
?>
<style>
#wrapper	{
	background-color: #EBEBEB;
	padding: 20px;
	margin: 0;
}
#wrapper input,
#wrapper select	{
	font-size: 22px;
	padding: 10px 15px;
	color: #222;
}
#wrapper select	{
	appearance: none;
	-webkit-appearance: none;
	border: 1px solid #333;
	box-shadow: 1px 1px 6px #333;
}
#wrapper label,
#wrapper input,
#wrapper select	{
	clear: both;
	float: left;
}
div	{
	margin-bottom: 10px;
}
p,h1,h2,h3,div,span	{
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
}
#testback	{
	margin-top: 20px;
	font-size:25px;
	padding: 10px 20px;
	background-color: green;
	display: inline-block;
	float: left;
	clear: both;
	color: #FFF;
}
</style>
<?php echo $ajax->library_def; ?>
<script>
<?php echo nUberJTools::DocumentReady($ajax->Construct()); ?>
</script>
<div id="wrapper">
	<div style="display: inline-block; width: 100%;">
		<div id="cookie" data-test="crumble">This text wrapper has a hidden <i>data</i> attribute called "cookie" that will send: <span style="color: red;">crumble</span></div>
	</div>
	<div style="display: inline-block; width: 100%;">
		<div class="get_some_text" id="sometxt_id"><span style="color: red;">-->This text will send in the ajax request.<--</span></div>
	</div>
	<form id="tester" action="/tester.php" class="myforms">
	<div style="display: inline-block; width: 100%;">
		<label>Type something, then tab to dropdown. The name of this &lt;input&gt; is "butter" however, will be overridden and sent to AJAX as "subutter":</label>
		<input type="text" name="butter" value="" />
	</div>
	
	<div style="display: inline-block; width: 100%;">
		<p>There is a hidden field here named "paste" that will send <span style="color: red;">"glue is great."</span> in the ajax request.</p>
		
		<input type="hidden" name="paste" value="glue is great." />
	</div>
	<div style="display: inline-block; width: 100%;">
		<label>This dropdown name is "that_class". Watch for it in the post</label>
		<select name="this_class">
			<option value="">PHPtoAjax</option>
			<option value="nope_not_great">This is great!</option>
			<option value="not_really">This is easy!</option>
			<option value="not_by_a_long_shot">This is so intuitive!</option>
		</select>
	</div>
		<!--<input type="submit" value="SUBMIT" />-->
	</form>
	<div style="display: inline-block; width: 100%;">
	<label>This field is outside of the form and named "getit"</label>
	<input name="getit" value="I got's it!" /> 
	</div>
</div>
<div id="testback">This is where the AJAX will load into. It's a &lt;div&gt; with id="testback"</div>
