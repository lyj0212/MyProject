<script>
    $(function() {

    	//가입여부확인
    	$('#check').on('click', function(e) {
				e.preventDefault();

				var this_ = this;
				var url = '<?php echo $this->link->get(array('action' => 'is_check'), TRUE); ?>';
				var email = $('input[name="userid"]').val();
				var name = $('input[name="userName"]').val();

				$.post(url, {email:email, name:name}, function(bool) {
                    $('._reno').css('display', 'none');
                    $('._recheck').css('display', 'block');
					if(bool == 'false')
					{
                      $('._show').css('display', 'none');
                      $('._hide').css('display', 'block');
					}
					else
					{
                        $('._show').css('display', 'block');
                        $('._hide').css('display', 'none');
                        $('#name').html(name);
                        $('#date').html(bool);
					}
				});
        });

    	//새로운 비번생성
        $('#_nowpasswd').on('click', function(e) {
            e.preventDefault();

            var this_ = this;
            var url = '<?php echo $this->link->get(array('action' => 'find_pwd'), TRUE); ?>';
            var userid = $('input[name="userid"]').val();
            var userName = $('input[name="userName"]').val();

            $.post(url, {userid:userid, userName:userName}, function(bool) {
                if(bool == 'true')
                {
                    location.href='<?php echo $this->link->get(array('action' => 'index'), TRUE); ?>';
                }
            });

        });

    });
</script>
