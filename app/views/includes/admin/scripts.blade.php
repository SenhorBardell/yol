<!-- jQuery 2.1.3 -->
<script src="<?php echo asset('plugins/jQuery/jQuery-2.1.3.min.js') ?>"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo asset('bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo asset('plugins/datatables/jquery.dataTables.js') ?>" type="text/javascript"></script>
<script src="<?php echo asset('plugins/datatables/dataTables.bootstrap.js') ?>" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?php echo asset('plugins/slimScroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo asset('plugins/fastclick/fastclick.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo asset('js/app.min.js') ?>" type="text/javascript"></script>
<!-- page script -->
<script type="text/javascript">
	$(function () {
		$("#example1").dataTable();
		$('#example2').dataTable({
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": true,
			"bInfo": false,
			"bAutoWidth": false
		});
	});
</script>
<script src="<?php echo asset('plugins/iCheck/icheck.min.js') ?>" type="text/javascript"></script>
<script>
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
	});
</script>
