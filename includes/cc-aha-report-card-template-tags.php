<?php
/*
 *
 *
 */

 //Overarching function to render report card
function cc_aha_render_report_card(){
	cc_aha_print_all_report_card_health();
}


// Major template pieces
function cc_aha_print_all_report_card_health( ) {
	
	//Get ALL board data.
	$all_data = cc_aha_get_all_board_data();
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	
	//var_dump($all_data);
	
	?>

	<h3 class="screamer">Summary Dashboard - All Boards</h3>

	<section id="summary-report-card" class="clear">
		<?php // Building out a table of responses for one metro
		?>
		<h4>Community Health Assessment Analysis</h4>
		
		<ul class="horizontal no-bullets">
			
			<li><a class="button community-hide-trigger">HIDE COMMUNITY</a></li>
			<li><a class="button school-hide-trigger">HIDE SCHOOL</a></li>
			<li><a class="button care-hide-trigger">HIDE CARE</a></li>
			
			<li class="alignright">
				<li><a class="button all-show-trigger">SHOW ALL</a></li>
			</li>
		</ul>
		<!--<span>
			Filter by State: <select name="state-select" id="state-dropdown-top">
				<option value="-1">All States</option>
				<?php
				foreach ( $state_array as $state_option ){ 
					$option_output = '<option value="';
					$option_output .= $state_option;
					$option_output .= '">';
					$option_output .= $state_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select>
			Filter by Affiliate: <select name="affiliate-select" id="affiliate-dropdown-top">
				<option value="-1">See all Affiliates</option>
				<?php
				foreach ( $affiliate_array as $affiliate_option ){ 
					$affiliate_nospace = str_replace(' ', '', $affiliate_option);
					$option_output = '<option value="';
					$option_output .= $affiliate_nospace;
					$option_output .= '">';
					$option_output .= $affiliate_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select>
		</span>-->
		<?php cc_aha_print_report_card_table( $all_data ); ?>

	</section>
	<?php 
} 

function cc_aha_print_report_card_table( $all_data ) {

	//get titles and subtitles of sections
	$sections = cc_aha_get_summary_sections();
	//TODO, remove these if we're going w version above
	$state_array = cc_aha_get_unique_board_states();
	$affiliate_array = cc_aha_get_unique_board_affiliates();
	//var_dump( $affiliate_array );
	
	//just testing a few.. TODO: remove this
	$data3 = array_slice ( $all_data , 0, 3);
	?>
	
	<table id="report-card-table" class="tablesorter">
		
		
	<?php 

		
		echo '</tr>';
		
		//because of tablesort's thead needs
		?>
		<thead>
			<tr class="overall-header">
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border community-show" colspan="6">Community Policies<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="5">Healthy Schools<br></th>
				
				<th class="{sorter: false} white-border care-show" colspan="3">Healthcare Quality and Access<br></th>
				<th class="">Total Score</th>
			</tr>
			
			<tr>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false}"></th>
				<th class="{sorter: false} white-border community-show" colspan="2">Tobacco<br></th>
				<th class="{sorter: false} white-border community-show" colspan="1">Physical Activity<br></th>
				<th class="{sorter: false} white-border community-show" colspan="3">Healthy Diet<br></th>
				
				<th class="{sorter: false} white-border school-show" colspan="2">Physical Activity<br></th>
				<th class="{sorter: false} white-border school-show" colspan="2">Healthy Diet<br></th>
				<th class="{sorter: false} white-border school-show" colspan="1">Chain of Survival<br></th>
				
				<th class="{sorter: false} white-border care-show" colspan="3">Healthy Outcomes<br></th>
				<th class="{sorter: false}"></th>
			</tr>
		
			<tr>
				<th class="">Board</th>
				<th class="">State</th>
				<th class="">Affiliate</th>
		
		<?php
		foreach ($sections as $section_name => $section_data) { 	
			//and again for the criterion
			// these need to be th so they are sortable (per jquery.tablesort.js)
			$hiding_class = $section_name . '-show';
			foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
				foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
				?>
					<th class="<?php echo $hiding_class; ?>">
						<?php echo $criteria_data['label']; ?>
					</th>
				

				<?php
				}
			}
		}
		
		//one more to account for total score
		echo '<th></th></tr>';
		
		//echo '</thead>';
		//4th row for state/affiliate sorting, as well as Top 3 selection
		//can't use th here, tablesorter is not letting dropdowns...dropdown
		echo '<tr class="overall-header { sorter: false }"><td></td>';
		?>
			<th class="state-select { sorter: false }"><select name="state-select" id="state-dropdown">
				<option value="-1">All</option>
				<?php
				foreach ( $state_array as $state_option ){ 
					$option_output = '<option value="';
					$option_output .= $state_option;
					$option_output .= '">';
					$option_output .= $state_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select></td>
			<th class="affiliate-select { sorter: false }"><select name="affiliate-select" id="affiliate-dropdown">
				<option value="-1">See all Affiliates</option>
				<?php
				foreach ( $affiliate_array as $affiliate_option ){ 
					$affiliate_nospace = str_replace(' ', '', $affiliate_option);
					$option_output = '<option value="';
					$option_output .= $affiliate_nospace;
					$option_output .= '">';
					$option_output .= $affiliate_option;
					$option_output .= '</option>';
					print $option_output;
					
				} ?>
				
			</select></td>
			
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
			<th class="{ sorter: false }"></th>
		
		<?php
		echo '</tr>';
		echo '</thead>';
		
		foreach( $all_data as $data ){
			$metro_id = $data['BOARD_ID']; 
			$total_score = 0;
			$state = $data['State'];
			
			$affiliate = $data['Affiliate'];
			//strip spaces from affiliate
			$affiliate = str_replace(' ', '', $affiliate);
			
			//$state_array[] = $state; //push this state onto the array for displaying?
			
			echo '<tr class="board-data ' . $state . ' ' . $affiliate . '">';
			echo '<td class="">' . $data['Board_Name'] . '</td>';
			echo '<td class="">' . $data['State'] . '</td>';
			echo '<td class="">' . $data['Affiliate'] . '</td>';
				foreach ($sections as $section_name => $section_data) {
				
					$hiding_class = $section_name . '-show';
					//and again for the criterion
					foreach ( $section_data['impact_areas'] as $impact_area_name => $impact_area_data ) {
						foreach ( $impact_area_data['criteria'] as $crit_key => $criteria_data ) {
							//$top3 = $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'];
							
							$top3Yes = $data[$section_name . '-' . $impact_area_name . '-' . $crit_key . '-top-3'] ? $criteria_data['group'] . '-top-3' : ''; ;
							
							$health_level = cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id );
							switch( $health_level ){
								case "healthy":
									$total_score += 2;
									break;
								case "intermediate":
									$total_score += 1;
									break;
								case "poor":
									$total_score += 0;
									break;							
							}
						?>
							<td class="<?php echo $health_level . ' ' . $hiding_class . ' ' . $top3Yes; ?>" title="<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>">
								<div class="hidden">
									<?php cc_aha_print_dial_label( cc_aha_section_get_score( $section_name, $impact_area_name, $crit_key, $metro_id ) ); ?>
								</div>
							</td>					

						<?php
						}
					}
				}
			
			//Insert total score based on calculations above
			$total_percent = intval( $total_score / 28 * 100 );
			echo '<td>' . $total_percent . '%<br />[=' . $total_score . '/28] </td>';
			
			echo '</tr>';
			//echo($data);
		}
		
	?>
	</table>
	
	<?php


}