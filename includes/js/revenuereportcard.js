


function reportCardClickListen(){

	//what state is selected in the drop down?
	jQuery('#state-dropdown').on("change", function(){
		var thisState = jQuery( "select#state-dropdown option:selected").val();
		filterByState( thisState);
	});

	//is an affiliate selected?
	jQuery('#affiliate-dropdown').on("change", function(){
		var thisAffiliate = jQuery( "select#affiliate-dropdown option:selected").val();
		filterByAffiliate( thisAffiliate);
	});
	
	//is top 3 selected?
	jQuery('tr.top-3-row th').click(function(){
		
		var whichTop3Yes = jQuery(this).data("top3group");
		var whichTop3Name = jQuery(this).data("top3name");
		
		filterByTop3( jQuery(this), whichTop3Yes, whichTop3Name );
		
	});
	
	//take care of our sort arrows
	jQuery('tr.criteria-row th').on("click", function(){
		
		//all arrows desc until further notice
		resetCriteriaArrows();
		
		//are we in asc or desc order?
		if ( jQuery(this).hasClass('tablesorter-headerAsc') ) {
			jQuery(this).find('.sort-arrow').html('&#x25B2;');
		} else {
			jQuery(this).find('.sort-arrow').html('&#x25BC;');
		}
		
	});
}

function resetCriteriaArrows(){
	jQuery('.sort-arrow').html('&#x25BC;');

}

function filterByState( state, fromTop3 ){
	//set default value for fromTop3, if not passed in
	fromTop3 = fromTop3 || false;
	
	//get other filters
	//if a state is selected, we will need to filter for it
	//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
	//var affiliateName = jQuery('#affiliate-dropdown').val();
	
	//if a top 3 is selected, we will need to filter for it
	var top3selected = jQuery('tr.top-3-row').find('.selected-top-3');
	
	if ( !( jQuery("tr.board-data." + state + "" ).is(":visible") ) && ( state != "-1") ) {
		jQuery("tr.board-data." + state + "" ).show();
	}
	
	if ( state != "-1" ) {
		jQuery("tr.board-data:not(." + state + " )").hide();
		
		//change print text to reflect state and ALL affiliates
		jQuery('ul#geography .state').html( state );
		jQuery('ul#geography .affiliate').html('All');
		
		//if an affiliate is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( affiliateName != "-1" ) {
			jQuery("tr.board-data:not(." + affiliateName + " )").hide();
		}*/
	} else {  //show all
		jQuery("tr.board-data").show();
		//change the print-only input to show all states
		jQuery('ul#geography .state').html('All');
		
		//if an affiliate is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( affiliateName != "-1" ) {
			jQuery("tr.board-data:not(." + affiliateName + " )").hide();
		}*/
	}
	
	//if we're not coming from top 3, run that filter, too
	if ( fromTop3 == false ){
		//now filter the results for top 3
		//top3selected.data("top3group");
		var whichTop3 = top3selected.data("top3group");
		
		if( whichTop3 != undefined ) {
			jQuery("tr.board-data:not(:has(." + whichTop3 + " ))").hide();
		}
			
		//replace the 'Board Priority' print text with the data-top3name
		//jQuery("ul#top3 .board-priority").html( whichTop3Name );
		//TODO: check print filter name things
		
		//recalculate the number of top 3 visible
		countTop3();
		
		//Affiliate/State filters cancel each other out (again) as of 7 Oct 2014
		//change the affiliate drop down to -1
		jQuery('#affiliate-dropdown').val("-1");
	}
	
	
	
}

function filterByAffiliate( affiliate, fromTop3 ){
	//set default value for fromTop3, if not passed in
	fromTop3 = fromTop3 || false;
	
	var affilateName;
	//get other filters
	//if a state is selected, we will need to filter for it
	//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
	//var stateName = jQuery('#state-dropdown').val();
	
	//if a top 3 is selected, we will need to filter for it
	var top3selected = jQuery('tr.top-3-row').find('.selected-top-3');
	
	//if the just-selected affiliate rows aren't visible, show them
	/*if ( !( jQuery("tr.board-data." + affiliate + "" ).is(":visible") ) && ( affiliate != "-1") ) {
		//jQuery("tr.board-data." + affiliate + "" ).fadeIn();
		jQuery("tr.board-data." + affiliate + "" ).show();
	}*/
	if ( affiliate != "-1" ) {
		//show all required affiliate rows
		jQuery("tr.board-data." + affiliate ).show();
		
		//if an affiliate is selected, hide others
		//jQuery("tr.board-data:not(." + affiliate + " )").fadeOut();
		jQuery("tr.board-data:not(." + affiliate + " )").hide();
		
		//update the print filter text
		//jQuery('ul#geography .affiliate').html( affiliate ); //this is value, not name
		affilateName = jQuery('th.affiliate-select select option:selected').text();
		affilateName = affilateName.replace('See all Affiliates','');
		jQuery('ul#geography .affiliate').html( affilateName );
		jQuery('ul#geography .state').html('All');
		
		//if a state is also selected, hide other rows
		//Nope - Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( stateName != "-1" ) {
			jQuery("tr.board-data:not(." + stateName + " )").hide();
		}*/
		
	} else {
		//jQuery("tr.board-data").fadeIn();
		jQuery("tr.board-data").show();
		
		//change the print-only input to show all affiliates
		jQuery('ul#geography .affiliate').html('All');
		
		//if a state is also selected, hide other rows
		//Affiliate/State filters cancel each other out as of 7 Oct 2014
		/*if ( stateName != "-1" ) {
			jQuery("tr.board-data:not(." + stateName + " )").hide();
		}*/
	}
	

	//if we're not coming from top3, run that filter, too
	if ( fromTop3 == false ){
		//now filter the results for top 3
		//top3selected.data("top3group");
		var whichTop3 = top3selected.data("top3group");
		
		if( whichTop3 != undefined ) {
			jQuery("tr.board-data:not(:has(." + whichTop3 + " ))").hide();
		}
			
		//replace the 'Board Priority' print text with the data-top3name
		//do we need to do this here?  TODO: check print filter info
		//jQuery("ul#top3 .board-priority").html( whichTop3Name );
		
		//recalculate the number of top 3 visible
		countTop3();
	
	
	}	
	
	//Affiliate/State filters cancel each other out (again) as of 7 Oct 2014
	//change the affiliate drop down to -1
	jQuery('#state-dropdown').val("-1");
	
	
}

function filterByTop3( thisObj, whichTop3Yes, whichTop3Name ) {

	//show all boards (fadeIn having rendering issues with background images not showing in FF)
	jQuery("tr.board-data").show();
	
	//filter by state OR affliate (7 Oct AHA chagne..)
	var stateName = jQuery('#state-dropdown').val();
	if ( stateName == '-1') {
		var affiliateName = jQuery('#affiliate-dropdown').val();
		//filter by state/affiliate, if necessary
		filterByAffiliate( affiliateName, true );
	} else {
	
		filterByState( stateName, true );
	}
	
	//we are deselecting ANY top 3 and showing all boards, minus geography filters (state, afflilate)
	if ( thisObj.hasClass('selected-top-3') ){
		thisObj.removeClass('selected-top-3');
				
		//replace the 'Board Priority' print text with 'None'
		jQuery("ul#top3 .board-priority").html('None Selected');
		
		//recalculate the number of top 3 visible
		countTop3();
		
	} else { //we are selecting a top 3 and hiding other board rows
		allTop3Buttons = jQuery('tr.top-3-row th');
		allTop3Buttons.removeClass('selected-top-3');
		thisObj.addClass('selected-top-3');
	
		//hide the rows that do not have the top 3 class
		jQuery("tr.board-data:not(:has(." + whichTop3Yes + " ))").hide();
		
		//replace the 'Board Priority' print text with the data-top3name
		jQuery("ul#top3 .board-priority").html( whichTop3Name );
		
		//console.log('top 3 count');
		//recalculate the number of top 3 visible
		//countTop3();
	}

}

function countTop3(){

	//get all th in top-3-row with class ending in -top-3 (report-card-table only, NOT report-card-table sticky
	var allTop3th = jQuery('#revenue-report-card-table tr.top-3-row th[class*=-top-3]');
	var top3group;
	var top3grouptd;
	var top3groupsize;
	
	allTop3th.each( function() {
		top3group = jQuery(this).data("top3group");
		
		//get number of tds with this top 3 group
		top3grouptd = jQuery('#revenue-report-card-table td.' + top3group + ':visible')
		
		//how many in table
		top3groupsize = top3grouptd.size();
		
		//update html to reflect number
		jQuery(this).find('.top-3-count').html( top3groupsize );
	
	});
	
}

//NOPE:add stars or border to all top-3 tbody tds
function top3stars(){

	//get top 3 tbody tds
	var top3boxes = jQuery('tr.board-data').children('[class*=top-3]');
	var innerbox;
	//var starImage = jQuery("img.star-image").attr("src");
	//var starImage = jQuery("img.star-image").data("lazy-src");
	top3boxes.each( function() {
		//if ( jQuery(this).is(":visible") ) {
		
			innerbox = jQuery(this).find(".top-3-image");
			
			//innerbox.html("<img src='" + starImage + "'>");
			innerbox.removeClass('hidden');
		//}

	});
}

jQuery(document).ready(function($){
	var options = {
		widgets: [ 'stickyHeaders', 'zebra' ],
		widgetOptions: {

		  // extra class name added to the sticky header row
		  stickyHeaders : 'tablesorter-stickyHeader',
		
		  
		}
  };
	//let tablesorter know we want to sort this guy
	$("#revenue-report-card-table").tablesorter(options); 

	reportCardClickListen();

	countTop3();
	//top3stars();
	
	
},(jQuery));
