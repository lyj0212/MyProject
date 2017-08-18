<script>
	$(function() {
		new daum.Postcode({
			width : '100%',
			onresize : function() {
				Resize_Box();
			},
			oncomplete : function(data) {
				var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
				var extraRoadAddr = ''; // 도로명 조합형 주소 변수

				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname))
				{
					extraRoadAddr += data.bname;
				}

				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y')
				{
					extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}

				// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraRoadAddr !== '')
				{
					extraRoadAddr = ' (' + extraRoadAddr + ')';
				}

				// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
				if(fullRoadAddr !== '')
				{
					fullRoadAddr += extraRoadAddr;
				}

				$('<?php echo $target_zip; ?>', parent.document).val(data.zonecode);
				$('<?php echo $target_address1; ?>', parent.document).val(fullRoadAddr);
				$('<?php echo $target_address2; ?>', parent.document).focus();

				<?php if($request == "true") : ?>
					parent.$('#writeForm').closest("form").submit();
				<?php endif; ?>

				parent.$.colorbox.close();
			}
		}).embed($('#_daum_zipcode').get(0));
	});
</script>