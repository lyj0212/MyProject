<script>
if(parent && parent != this)
{
	alert('권한이 없습니다.');
	try{ parent.$.colorbox.close(); } catch(e) { self.close(); }
}
</script>
