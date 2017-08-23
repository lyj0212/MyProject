<script type="text/javascript">
	$(function($) {
		var tocken = "<?php echo $access_token; ?>"; /* Access Tocken 입력 */
		var count = "6";

		$.ajax({
			type: "GET",
			dataType: "jsonp",
			cache: false,
			url: "https://api.instagram.com/v1/users/self/media/recent/?access_token=" + tocken + "&count=" + count,
			success: function(response) {
				if ( response.data.length > 0 ) {
					$.post(
						'<?php echo $this->link->get(array('action'=>'sns_do_login')); ?>', {
							'sns' : 'insta',
							'snsid' : response.data[0].id,
							'name' : response.data[0].user.full_name,
							'email' : response.data[0].user.username,
							'picture' : response.data[0].user.profile_picture
						},
						function(redirect) {
							location.replace(redirect);
						}
					);
				}
			}
		});

	});
</script>

