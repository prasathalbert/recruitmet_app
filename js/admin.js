// JavaScript Document
$(document).ready( function() {				
				$('.body_container #datatable').dataTable( {
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
                    "fnDrawCallback": function( oSettings ) {
                      $(".body_container").css('height','auto');
                    }
				});
				
			});