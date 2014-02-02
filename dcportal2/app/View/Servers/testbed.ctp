
<?php
        echo $this->Html->meta('icon','favicon.png');
      // echo $this->Html->css('redmond/jquery.ui.all.css'); //Replaced temporay by below line
        
        echo $this->Html->css('datepicker');
        echo $this->Html->css('jquery-ui-1.10.3');
        
        echo $this->Html->script('jquery'); //Replaced temporay with below line
        echo $this->Html->script('utils');
        echo $this->Html->script('eye');
        echo $this->Html->script('layout');
        echo $this->Html->script('datepicker');
        //echo $this->Html->script('jquery-1.9.1');\
        echo $this->Html->script('jquery-ui-1.8.2.custom.min');        
        //echo $this->Html->script('fg.menu');
        echo $this->Html->script('themeswitchertool');
        //echo $this->Html->script('jquery.themeswitcher.js');
        //echo $this->Html->script('jquery.blockUI');
?>

<input id="inputDate" class="inputDate" value="06/14/2008"></input>
<div id="datepicker"></div>


<script type="text/javascript">
    
     $(document).ready(function(){
         
        if (jQuery) {
            alert("jQuery library is loaded!");
        }

         (function($) {

        
        $('#inputDate').DatePicker({
	format:'m/d/Y',
	date: $('#inputDate').val(),
	current: $('#inputDate').val(),
	starts: 1,
	position: 'r',
	onBeforeShow: function(){
		$('#inputDate').DatePickerSetDate($('#inputDate').val(), true);
	},
	onChange: function(formated, dates){
		$('#inputDate').val(formated);
		if ($('#closeOnSelect input').attr('checked')) {
			$('#inputDate').DatePickerHide();
		}
	}
    });
    
    
    $("#datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(dateText, inst) { $('#data').val(dateText); }
                });
     }

</script>