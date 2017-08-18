<script>
$('select[name="category"]').on('change', function() {
	$('.form-search').find('input[name="category"]').val($(this).val()).end().submit();
});
</script>
