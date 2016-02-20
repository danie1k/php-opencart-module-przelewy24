$(function(){
	$('form input').on('keypress', function(e){
		if((undefined !== e.keyCode) && (e.keyCode == 13)){
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	});

	$('form input#sort_order').on('keydown', function(e){
		if(undefined !== e.keyCode){
			if(e.keyCode == 33 || e.keyCode == 38){
				$('#sort_order_more').click();
			}
			if(e.keyCode == 34 || e.keyCode == 40){
				$('#sort_order_less').click();
			}

			if(e.keyCode == 33 || e.keyCode == 38 || e.keyCode == 34 || e.keyCode == 40){
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		}
	});
	
	$('.form-group.has-error .error-field').on('shown.bs.popover', function(){
		var caller = this;
		$(caller).next('.popover').on('click tap', function(){
			$(caller).popover('hide');
		});
	})

	$('.has-error .error-field').popover('show', {trigger:'manual'});

	$('#sort_order_more, #sort_order_less').on('click tap', function(e){
		var currentValue = parseInt($('#sort_order').val());
		if(isNaN(currentValue)) currentValue = 0;

		var action = $(e.currentTarget).prop('id');
		if(action == 'sort_order_more'){
			currentValue += 1;
		}
		if(action == 'sort_order_less'){
			currentValue -= 1;
			if(currentValue < 0){
				currentValue = 0;
			}
		}
		$('#sort_order').val(currentValue)
	});

	$('.edit_order_status').on('click tap', function(){
		var fieldId = $(this).attr('data-order-status');
		var orderStatusId = $('#'+fieldId+' > option:selected').val();
		window.open($(this).attr('data-edit-url')+'&order_status_id='+orderStatusId);
	});
	
	function confirmSubmit(e){
		if(confirm($('meta[name=config_save_warning]').attr('content'))){
			return true;
		}
		else{
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	}
	
	$('#change_store').on('change', function(e){
		if(confirmSubmit(e)) document.getElementById('config_multistore').submit();
	});
	
	$('#config_multistore').on('submit', function(e){ confirmSubmit(e); });
	$('#paymentsUpdate button[type=submit]').on('click tap', function(e){ confirmSubmit(e); });

	$('button[name=resetConfiguration]').on('click tap', function(e){
		if(confirm($(this).attr('data-message'))){
			return true;
		}
		else{
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	});
	
	$('input[name=sms_mode]').change(function(e){
		var smsMode = parseInt(e.currentTarget.value);
		if(smsMode){
			$('.sms_mode_hidden').slideUp();
			$('.sms_mode_shown').slideDown();
		}
		else{
			$('.sms_mode_hidden').slideDown();
			$('.sms_mode_shown').slideUp();
		}
	});
	
	$('input[name=payment_methods]').change(function(e){
		var selectedMethod = e.currentTarget.value;
		$('.payment_method_selected').slideUp().removeClass('payment_method_selected');
		$('#payment_method'+selectedMethod).slideDown().addClass('payment_method_selected');
	});

	$('#selectedPayments').multiSelect({
		selectableHeader: '<p class="text-center"><strong>'+$('#selectedPayments').attr('data-selectable-header')+'</strong></p>',
		selectionHeader: '<p class="text-center"><strong>'+$('#selectedPayments').attr('data-selection-header')+'</strong></p>',
		afterSelect: function(values){
			var optionText = $('#payment_methods_sel > option[value='+values[0]+']').text();
			$('#payment_methods_sel_default').append('<option value="'+values[0]+'">'+optionText+'</option>');
		},
		afterDeselect: function(values){
			$('#payment_methods_sel_default > option[value='+values[0]+']').remove();
		}

	});

	$('#paymentsFancyPreview').on('click tap', function(e){
		e.preventDefault();
		e.stopPropagation();
		window.open($(this).attr('href'), 'p24preview', 'status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0,scrollbars=0,height=500,width=800');
		return false;
	});

    $('#langEmails li > a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
    })
});