<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>
			SugarES
		</title>
		<link rel="icon" type="image/png" href="sugar_icon.ico">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		
	    <style type="text/css">
			/* Large desktop */
			@media (min-width: 980px) {
				body {
					padding-top: 60px;
				}
				.linediv-l {
					border-right: 1px white solid;
				}
				.linediv-r {
					border-left: 1px white solid;
				}
				
				.container{
					padding-left:10px;
				}
			}

			/* Landscape phones and down */
			@media (max-width: 480px) {
				.copy {
					padding: 2.5% 10%;
				}
				.linediv-l {
					border-bottom: 1px white solid;
				}
				.linediv-r {
					border-top: 1px white solid;
				}
			}

	      	/* All form factors */
			/* Main body and headings */
			body{
				font-family: 'Open Sans', Helvetica, Arial, sans-serif;
			}
			.heading, .subheading {
				font-family: 'Ubuntu', Helvetica, Arial, sans-serif;
				text-align: center;
			}
			
			ul.treeAction{
				list-style-type: none;
				padding: 0px;
				font-size: 12px;
			}
			
			label{
				cursor:default;
			}
		</style>
	</head>
	<body>
		
<?php 
	
	$filterMap = array(
    	'inputServerName'   => FILTER_SANITIZE_ENCODED,
    	'inputPort'   => FILTER_SANITIZE_ENCODED,
    	'inputIndex'   => FILTER_SANITIZE_ENCODED,
    	'searchResultCount' => FILTER_VALIDATE_INT,
    	'treeResultCount' => FILTER_VALIDATE_INT,
	);

	$getInputs = filter_input_array(INPUT_GET, $filterMap);

	//Connection Form
	$inputServerName = ( isset($getInputs["inputServerName"]) ? $getInputs["inputServerName"] :  "localhost" );
	$inputPort = ( isset($getInputs["inputPort"]) ? $getInputs["inputPort"] : "9200" );
	$inputIndex = ( isset($getInputs["inputIndex"]) ? $getInputs["inputIndex"] : "" );

	//Settings
	$searchResultCount = ( isset($getInputs["searchResultCount"]) ? $getInputs["searchResultCount"] : "50" );
	$treeResultCount = ( isset($getInputs["treeResultCount"]) ? $getInputs["treeResultCount"] : "10" );

	$settingsPassThroughAJAX = "searchResultCount: $searchResultCount,treeResultCount: $treeResultCount";

?>

		<input type="hidden" id="activeDocRecordIndex" />
		<input type="hidden" id="activeDocRecordType" />
		<input type="hidden" id="activeDocRecordId" />
		
		<input type="hidden" id="previousContentId" value="1" />
		
		<!-- nav bar -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container" style="width:100%;">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
          			</button>
          			<a class="brand" href="index.php">SugarES</a>
          			<div class="nav-collapse collapse">
            			<ul class="nav">
              				<li class="active"><a href="index.php">Home</a></li>
              				<li><a href="#aboutModal" data-toggle="modal">About</a></li>
              				<li><a href="#contactModal" data-toggle="modal">Contact</a></li>
            			</ul>
          			</div><!--/.nav-collapse -->
        		</div>
      		</div>
    	</div>
		
		<!-- body -->
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span3 well">
					<!--Sidebar content-->
					<!-- Connection Form -->
					<form action='index.php' class="form-inline" id="serverConnection">
						<div class="row-fluid">
							<label class="span4" for="inputServerName">Server</label>
  							<input class="span8" type="text" id="inputServerName" name="inputServerName" value="<?php echo $inputServerName; ?>" tabindex="1" />
						</div>
						
						<div class="row-fluid">
							<label class="span4" for="inputPort">Port</label>
  							<input class="span8" type="text" id="inputPort" name="inputPort" value="<?php echo $inputPort; ?>" tabindex="2" />
						</div>
						
						<div class="row-fluid">
							<label class="span4" for="inputIndex">Index</label>
  							<input class="span8" type="text" id="inputIndex" name="inputIndex" placeholder="(optional)" value = "<?php echo $inputIndex; ?>" />
						</div>
						
						<div class="row-fluid">
							<div class="form-actions">
								<button id="serverConnectionSubmit" type="submit" class="btn btn-primary pull-right" tabindex="3">
									<i class="icon-refresh icon-white"></i>
									Load
								</button>
							</div>
						</div>
					</form>
					<!-- Actions Tab Pane -->
					<div class="row-fluid">
						<div class="span12 well well-small">
							<div class="tabbable" style="margin-bottom: 18px;">
								<ul class="nav nav-tabs">
                					<li class="active"><a href="#treeContent" data-toggle="tab">Tree</a></li>
                					<li><a href="#injectContent" data-toggle="tab">Inject</a></li>
                					<li><a href="#searchContent" data-toggle="tab">Search</a></li>
              					</ul>
								<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                					<div class="tab-pane active" id="treeContent">
                						Enter in the ElasticSearch Server Properties and click Load.
                					</div>
                					<div class="tab-pane" id="injectContent">
                  						<!-- Inject Form -->
                					</div>
                					<!-- Search Tab -->
                					<div class="tab-pane" id="searchContent">
                						<!-- Search Form -->
										
                					</div> <!-- /Search Tab -->
              					</div>
            				</div> <!-- /tabbable -->
						</div>
					</div>
				</div>
				<div id="bodyContent" class="span8">
					<!--Body content-->
					
					<div class="row-fluid hidden-phone">
						
						<!-- Server Stats -->
						<div class="span6" id="serverStatsContent">
							<fieldset>
    							<legend>Server Stats</legend>
    							<div class="row-fluid">
    								<label class="span12">No Server Loaded.</label>
    							</div>
  							</fieldset>
						</div>
						
						<!-- Index Stats -->
						<div class="span6 hidden-phone" id="activeIndexStatsContent">
							<fieldset>
    							<legend>Index Stats</legend>
    							<div class="row-fluid">
    								<label class="span12">No Active Index Selected.</label>
    							</div>
  							</fieldset>
						</div>
					</div>
					
					<div class="row-fluid" id="mainContent">
						<!-- Main Content -->
						<div id="mainContentEmpty"></div>
					</div>
					
					<div class="row-fluid" id="errorResultsContent">
						<!-- Error Results -->
    				</div>
				</div>
			</div>
		</div>
		
		<!-- About Modal -->
		<div id="aboutModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    			<h3 id="aboutModalLabel">About</h3>
  			</div>
  			<div class="modal-body">
    			<p>
    				SugarES is designed to enable users to maintain their ElasticSearch server in relation to SugarCRM data.<br />
    				<ul>
    					<li>
    						Navigate multiple indicies and browse data through the Tree interface.
    					</li>
    					<li>
    						Create data into an index in ElasticSearch through the Inject interface.
    					</li>
    					<li>
    						Perform manual searches on the data in ElasticSearch through the Search interface.
    					</li>
    				</ul>
    			</p>
    			<p><a href="https://github.com/meveridge/SugarES">https://github.com/meveridge/SugarES</a></p>
    			<p>
    				Third party packages used: <br />
    				<ul>
    					<li><a href="http://jquery.com/">jQuery</a> -- jQuery is a fast and concise JavaScript Library that simplifies HTML document traversing, event handling, animating, and Ajax interactions for rapid web development.</li>
    					<li><a href="http://getbootstrap.com/">Twitterbootstrap</a> -- HTML, CSS, and JS toolkit from Twitter.</li>
    				</ul>
    			</p>
  			</div>
  			<div class="modal-footer">
    			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  			</div>
		</div>
		<!-- End About Modal -->
		
		<!-- Contact Modal -->
		<div id="contactModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    			<h3 id="contactModalLabel">Contact</h3>
  			</div>
  			<div class="modal-body">
  				<p>For any issues, feature requests, or general comments please create Issue records or Comments through GitHub.</p>
    			<p><a href="https://github.com/meveridge/SugarES">https://github.com/meveridge/SugarES</a></p>
  			</div>
  			<div class="modal-footer">
    			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  			</div>
		</div>
		<!-- End Contact Modal -->
		
		<!-- Loading Modal -->
		<div id="loadingModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
  			<div class="modal-body">
  				<p><img src="spinner.gif" />Loading...</p>
  			</div>
		</div>
		<!-- Loading Modal -->

		<script src="bootstrap/js/jquery-2.0.3.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/bootstrap-transition.js"></script>
		<script src="bootstrap/js/bootstrap-tab.js"></script>
		<script src="bootstrap/js/bootstrap-button.js"></script>
		<script src="bootstrap/js/bootstrap-collapse.js"></script>
		<script src="bootstrap/js/bootstrap-tooltip.js"></script>
		<script src="bootstrap/js/bootstrap-popover.js"></script>

		<script type="text/javascript">
			
			//capture the form submit and process the ajax
			/* attach a submit handler to the form */
			$("#serverConnection").submit(function serverConnectionSubmit(event) {
				
				$('#loadingModal').modal('show')
				
  				/* stop form from submitting normally */
  				event.preventDefault();
  				
  				//clear main content
  				$("#mainContent").empty();

  				/* get some values from elements on the page: */
  				var $form = $( this ),
  					action = "serverConnection",
      				inputServerName = $form.find( 'input[name="inputServerName"]' ).val(),
      				inputPort = $form.find( 'input[name="inputPort"]' ).val(),
      				inputIndex = $form.find( 'input[name="inputIndex"]' ).val(),
      				url = "controller.php";

  				/* Send the data using post */
  				var posting = $.post( url, { 
  					action: action, 
  					inputServerName: inputServerName, 
  					inputPort: inputPort, 
  					inputIndex: inputIndex,
  					<?php echo $settingsPassThroughAJAX; ?>
  				} );

  				/* Put the results in a div */
  				posting.done(function( data ) {
  					
  					//tab structures
    				var treeContent = $(data).find('#treeHTML');
    				$("#treeContent").empty().append(treeContent);
    				
    				var searchHTML = $(data).find('#searchHTML');
    				$("#searchContent").empty().append(searchHTML);

    				var injectHTML = $(data).find('#injectHTML');
    				$("#injectContent").empty().append(injectHTML);
    				//end tab structrures
    				
    				var serverStatsHTML = $(data).find('#serverStatsHTML');
    				$("#serverStatsContent").empty().append(serverStatsHTML);
    				
    				var indexStatsHTML = $(data).find('#indexStatsHTML');
    				var activeIndexId = $(data).find('#activeIndexId').val();
    				
    				//put all index stats into hidden div, to be revealed when selecting an index
    				$("#activeIndexStatsContent").empty().append(indexStatsHTML);
    				$("#"+activeIndexId).css("display","inline");
    				
    				var errorHTML = $(data).find('#errorHTML');
    				$("#errorResultsContent").empty().append(errorHTML);
    				
    				//$('#loadingModal').hide();
    				$('#loadingModal').modal('hide')
  				});
			});

			function populateMainContent(newMainContent){
	  			if($("#mainContent").contents().length==0){
	  				//main section is empty
	  			}else{
	  				//main section has something in it... 
	  					
	  				//create previous section
	  				var rndmNumber = $("#previousContentId").val();
	  				$('#previousContentId').val( function(i, oldval) {
						return ++oldval;
					});
	  				var preIdString = "";
	  				preIdString = "previousMainContent_" + rndmNumber;
	  				
	  				$("#bodyContent").append("<div class='row-fluid hidden' id='"+preIdString+"'><!-- Previous Main Content --></div>");
	  					
	  				//move content into it
	  				$("#"+preIdString).empty().append($("#mainContent").contents());
	  					
	  				//then empty main and add back button
	  				$("#mainContent").empty().append("<a href=\"javascript:loadBackContents('"+preIdString+"')\"><i class='icon-chevron-left'></i></a>");
	  			}
	  			$("#mainContent").append(newMainContent);
			}
			
			function loadBackContents(preIdString){
				
				//replace main content area with the previous contents area
				$('#mainContent').empty().append($('#'+preIdString).contents());
				
				//retreive doc metadata
				var inputIndex = $('#mainContent').find( 'input[name="thisContentsIndex"]' ).val();
				var inputType = $('#mainContent').find( 'input[name="thisContentsType"]' ).val();
				var inputId = $('#mainContent').find( 'input[name="thisContentsId"]' ).val();

				highlightSelectedRecord(inputIndex,inputType,inputId,false);
			}

			function changeActiveIndex(newActiveIndex){
				var oldActiveIndexId = $("#activeIndexId").val();
				$("#"+oldActiveIndexId).css("display","none");
				$("#activeIndexId").val("index_stats_"+newActiveIndex);
				$("#index_stats_"+newActiveIndex).css("display","inline");
				
				//auto select the index in the search and inject dds
				$("#inputIndexSelect_search").val(newActiveIndex);
				$("#inputIndexSelect_inject").val(newActiveIndex);

			}
			
			function changeTreeIcon(inputIndex,inputType){
				if(inputType==""){
					var iconId = inputIndex + "_icon";
				}else{
					var iconId = inputIndex + "_" + inputType + "_icon";
				}
				var newClass = ""; 
				if($("#"+iconId).hasClass("icon-plus-sign")){
					$("#"+iconId).removeClass("icon-plus-sign");
					$("#"+iconId).addClass("icon-minus-sign",true);
					newClass = "icon-minus-sign";
				}else{
					$("#"+iconId).addClass("icon-plus-sign",true);
					$("#"+iconId).removeClass("icon-minus-sign",true);
					newClass = "icon-plus-sign";
					
					if(inputType!=""){
						var activeDocRecordIndex = $("#activeDocRecordIndex").val();
	  					var activeDocRecordType = $("#activeDocRecordType").val();
	  					
	  					if(activeDocRecordIndex==inputIndex && activeDocRecordType == inputType){
	  						$("#mainContent").empty();
	  					}
					}
					
				}
				return newClass;
			}
			
			function retrieveDocsByIndexAndType(inputIndex,inputType){
				
				var targetId = inputIndex + "_" + inputType + "_child";
				var iconId = inputIndex + "_" + inputType + "_icon";
				
				var newClass = changeTreeIcon(inputIndex,inputType);
				
				if(newClass == "icon-minus-sign"){
					//only run ajax if icon is minus sign, meaning the section is open
					//get some values from elements on the page:
	  				var $form = $("#serverConnection"),
	  					action = "retrieveDocsByIndexAndType",
	      				inputServerName = $form.find( 'input[name="inputServerName"]' ).val(),
	      				inputPort = $form.find( 'input[name="inputPort"]' ).val(),
	      				url = "controller.php";
	
	  				//Send the data using post
	  				var posting = $.post( url, { 
	  					action: action, 
	  					inputServerName: inputServerName, 
	  					inputPort: inputPort, 
	  					inputIndex: inputIndex, 
	  					inputType: inputType,
	  					<?php echo $settingsPassThroughAJAX; ?>
	  				} );
	
	  				//Put the results in a div
	  				posting.done(function(data) {
	
	  					var docTreeHTML = $(data).find('#docTreeHTML');
	    				$("#"+targetId).empty().append(docTreeHTML);
	    				
	    				var errorHTML = $(data).find('#errorHTML');
	    				$("#errorResultsContent").empty().append(errorHTML);
	  				});
  				
  				}else{
  					//collapse tree, clear the results
  					//but only if we collapse the active record section
  					
  					var activeDocRecordIndex = $("#activeDocRecordIndex").val();
  					var activeDocRecordType = $("#activeDocRecordType").val();
  					
  					if(activeDocRecordIndex==inputIndex && activeDocRecordType == inputType){
  						$("#mainContent").empty();
  					}
  				}
			}
			
			function highlightSelectedRecord(inputIndex,inputType,inputId,createBackHiddenFields){
				
				activeDocRecordId = $("#activeDocRecordId").val()
				
				//tree record highlighting
				if(activeDocRecordId!=""){
					//remove last record's highlight
					$("#"+$("#activeDocRecordIndex").val()+"_"+$("#activeDocRecordType").val()+"_tree_"+$("#activeDocRecordId").val()).removeClass("muted");
					$("#"+$("#activeDocRecordIndex").val()+"_"+$("#activeDocRecordType").val()+"_tree_"+$("#activeDocRecordId").val()+"_icon").removeClass("icon-arrow-right");
					
					if(createBackHiddenFields){
							
						//store for retrieval via back button
						$('#docHTML').append("<input type='hidden' name='thisContentsIndex' value='"+$("#activeDocRecordIndex").val()+"' />");
						$('#docHTML').append("<input type='hidden' name='thisContentsType' value='"+$("#activeDocRecordType").val()+"' />");
						$('#docHTML').append("<input type='hidden' name='thisContentsId' value='"+$("#activeDocRecordId").val()+"' />");
					}
				}
				$("#activeDocRecordIndex").val(inputIndex);
				$("#activeDocRecordType").val(inputType);
				$("#activeDocRecordId").val(inputId);
				
				$("#"+inputIndex+"_"+inputType+"_tree_"+inputId).addClass("muted");
				$("#"+inputIndex+"_"+inputType+"_tree_"+inputId+"_icon").addClass("icon-arrow-right");
			}
			
			function retrieveDocById(inputIndex,inputType,inputId){
				var $form = $("#serverConnection"),
					activeDocRecordId = $("#activeDocRecordId").val(),
	  				action = "retrieveDocById",
	      			inputServerName = $form.find( 'input[name="inputServerName"]' ).val(),
	      			inputPort = $form.find( 'input[name="inputPort"]' ).val(),
	      			url = "controller.php";
				
				//change the arrow in the tree for the correct record
				highlightSelectedRecord(inputIndex,inputType,inputId,true);
				
	  			//Send the data using post
	  			var posting = $.post( url, { 
	  				action: action, 
	  				inputServerName: inputServerName, 
	  				inputPort: inputPort, 
	  				inputIndex: inputIndex, 
	  				inputType: inputType, 
	  				inputId: inputId,
	  				<?php echo $settingsPassThroughAJAX; ?>
	  			} );
	
	  			//Put the results in a div
	  			posting.done(function(data) {
	
	  				var docHTML = $(data).find('#docHTML');
	  				populateMainContent(docHTML);
	   				//$("#mainContent").empty().append(docHTML);
	   				var errorHTML = $(data).find('#errorHTML');
	   				$("#errorResultsContent").empty().append(errorHTML);
				});
			}
			
			function retrieveDocsByQuery(){
  				
				var $formConnection = $("#serverConnection"),
	      			inputServerName = $formConnection.find( 'input[name="inputServerName"]' ).val(),
	      			inputPort = $formConnection.find( 'input[name="inputPort"]' ).val();
	      		var $formSearch = $("#search"),
	      			inputIndexSelect = $formSearch.find( 'select[name="inputIndexSelect_search"]' ).val(),
	      			inputTypeSelect = $formSearch.find( 'select[name="inputTypeSelect_search"]' ).val(),
	      			inputIdQuery = $formSearch.find( 'input[name="inputIdQuery_search"]' ).val(),
	      			inputQueryString = $formSearch.find( 'input[name="inputQueryString_search"]' ).val(),
	      			action = "retrieveDocsByQuery",
	      			url = "controller.php";

	  			//Send the data using post
	  			var posting = $.post( url, { 
	  				action: action, 
	  				inputServerName: inputServerName, 
	  				inputPort: inputPort, 
	  				inputIndexSelect: inputIndexSelect, 
	  				inputTypeSelect: inputTypeSelect, 
	  				inputIdQuery: inputIdQuery, 
	  				inputQueryString: inputQueryString,
	  				<?php echo $settingsPassThroughAJAX; ?> 
	  			} );
	
	  			//Put the results in a div
	  			posting.done(function(data) {
	
	  				var docResultsHTML = $(data).find('#docResultsHTML');
	  				populateMainContent(docResultsHTML);
	  				
	   				var errorHTML = $(data).find('#errorHTML');
	   				$("#errorResultsContent").empty().append(errorHTML);
				});
			}

			function retrieveMoreDocsByType(type){
				$("#search").find( 'select[name="inputTypeSelect_search"]' ).val(type);
				retrieveDocsByQuery();
			}

			function injectDoc(){
  				
				var $formConnection = $("#serverConnection"),
	      			inputServerName = $formConnection.find( 'input[name="inputServerName"]' ).val(),
	      			inputPort = $formConnection.find( 'input[name="inputPort"]' ).val();
	      		var $formSearch = $("#inject"),
	      			inputIndexSelect = $formSearch.find( 'select[name="inputIndexSelect_inject"]' ).val(),
	      			inputTypeSelect = $formSearch.find( 'select[name="inputTypeSelect_inject"]' ).val(),
	      			inputID = $formSearch.find( 'input[name="inputFieldID_inject"]' ).val(),
	      			action = "injectDoc",
	      			url = "controller.php";

	      		//get variable fields
	      		var currentSelection = $("#current_inputIndexSelect_inject").val();
	      		var varFields = $("#"+currentSelection+" :input");
	      		var fieldsJSON = "";
	      		var fieldsArray = new Array();

	      		varFields.each(function( index ) {
	      			fieldsJSON = fieldsJSON + "\""+$(this).attr("name")+"\": \""+$(this).val()+"\",";
	      			if($(this).attr("name")!="module") $(this).val("");
	      		});

	      		if(inputTypeSelect==""){
	      			alert("Please select a Document Type.");
	      		}else{

	      			$formSearch.find( 'input[name="inputFieldID_inject"]' ).val("");

		  			//Send the data using post
		  			var posting = $.post( url, { 
		  				action: action, 
		  				inputServerName: inputServerName, 
		  				inputPort: inputPort, 
		  				inputIndexSelect: inputIndexSelect, 
		  				inputTypeSelect: inputTypeSelect, 
		  				inputID: inputID, 
		  				fieldsJSON: fieldsJSON,
		  				<?php echo $settingsPassThroughAJAX; ?>
		  			} );
		
		  			//Put the results in a div
		  			posting.done(function(data) {
		
		  				var docResultsHTML = $(data).find('#docResultsHTML');
		  				populateMainContent(docResultsHTML);
		  				
		   				var errorHTML = $(data).find('#errorHTML');
		   				$("#errorResultsContent").empty().append(errorHTML);
					});
	  			}
			}
		</script>
	</body>
</html>