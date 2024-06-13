		</div>

		</div>

		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<script src="js/select2.full.min.js"></script>
		<script src="js/jquery.inputmask.js"></script>
		<script src="js/jquery.inputmask.date.extensions.js"></script>
		<script src="js/jquery.inputmask.extensions.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="js/icheck.min.js"></script>
		<script src="js/fastclick.js"></script>
		<script src="js/jquery.sparkline.min.js"></script>
		<script src="js/jquery.slimscroll.min.js"></script>
		<script src="js/jquery.fancybox.pack.js"></script>
		<script src="js/app.min.js"></script>
		<script src="js/jscolor.js"></script>
		<script src="js/on-off-switch.js"></script>
		<script src="js/on-off-switch-onload.js"></script>
		<script src="js/summernote.js"></script>
		<script src="js/demo.js"></script>
		<script src="js/jquery.validate.min.js" type="text/javascript"></script>
		<script src="js/plugins/tinymce/js/tinymce/tinymce.min.js"></script>
		<script src="js/allOption.js"></script>
		<script src="js/tagify.min.js"></script>

		<script>
			var input = document.querySelector('input[name=tags]');

			var tagify = new Tagify(input);


			$("#click").on("click", function() {
				var n = "ADD_IT_" + Math.random();
				var tags = [];
				tags.push(n);
				tagify.addTags(tags);
			})

			
		</script>

		<script>
			function convertSlugOutput(title, urlelementId) {
				if (title.value !== "") {
					var str = title.value;
					str = str.toLowerCase().replace(/[^\w ]+/g, '').replace(/[^a-zA-Z0-9&]+/g, '-').replace(/-+$/, '');
					var r = confirm("URL: ''" + str + "' is genrated automatically. Do you wish to set new url?");
					if (r) {
						document.getElementById(urlelementId).value = str;
					}
				}
			}
		</script>
		<script>
			function myFunction() {
				var copyText = document.getElementById("myArea");
				copyText.select();
				copyText.setSelectionRange(0, 99999)
				document.execCommand("copy");
				alert(copyText.value);
			}
		</script>

		<script>
			$(function() {

				$(document).ready(function() {
					$('#editor1').summernote({
						height: 500
					});
					$('#editor2').summernote({
						height: 500
					});
					$('#editor3').summernote({
						height: 500
					});
					$('#editor4').summernote({
						height: 300
					});
					$('#editor5').summernote({
						height: 300
					});
					$('#editor_short').summernote({
						height: 100
					});
				});


				//Initialize Select2 Elements
				$(".select2").select2();

				//Datemask dd/mm/yyyy
				$("#datemask").inputmask("dd-mm-yyyy", {
					"placeholder": "dd-mm-yyyy"
				});
				//Datemask2 mm/dd/yyyy
				$("#datemask2").inputmask("mm-dd-yyyy", {
					"placeholder": "mm-dd-yyyy"
				});
				//Money Euro
				$("[data-mask]").inputmask();

				//Date picker
				$('#datepicker').datepicker({
					autoclose: true,
					format: 'dd-mm-yyyy',
					todayBtn: 'linked',
				});

				$('#datepicker1').datepicker({
					autoclose: true,
					format: 'dd-mm-yyyy',
					todayBtn: 'linked',
				});

				$('#datepicker2').datepicker({
					autoclose: true,
					format: 'yyyy',
					todayBtn: 'linked',
				});

				//iCheck for checkbox and radio inputs
				$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
					checkboxClass: 'icheckbox_minimal-blue',
					radioClass: 'iradio_minimal-blue'
				});
				//Red color scheme for iCheck
				$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
					checkboxClass: 'icheckbox_minimal-red',
					radioClass: 'iradio_minimal-red'
				});
				//Flat red color scheme for iCheck
				$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
					checkboxClass: 'icheckbox_flat-green',
					radioClass: 'iradio_flat-green'
				});



				$("#example1").DataTable();
				$('#example2').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});

				$('#confirm-delete').on('show.bs.modal', function(e) {
					$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
				});

			});
		</script>

		<script type="text/javascript">
			function showDiv(elem) {
				if (elem.value == 0) {
					document.getElementById('photo_div').style.display = "none";
					document.getElementById('icon_div').style.display = "none";
				}
				if (elem.value == 1) {
					document.getElementById('photo_div').style.display = "block";
					document.getElementById('photo_div_existing').style.display = "block";
					document.getElementById('icon_div').style.display = "none";
				}
				if (elem.value == 2) {
					document.getElementById('photo_div').style.display = "none";
					document.getElementById('photo_div_existing').style.display = "none";
					document.getElementById('icon_div').style.display = "block";
				}
			}

			function showContentInputArea(elem) {
				if (elem.value == 'Full Width Page Layout') {
					document.getElementById('showPageContent').style.display = "block";
				} else {
					document.getElementById('showPageContent').style.display = "none";
				}
			}
		</script>

		<script type="text/javascript">
			$(document).ready(function() {

				$("#btnAddFaq").click(function() {

					var trNew1 = "";

					var fqTitle = '<input autocomplete="off" type="text" class="form-control" name="fq_title[]" style="width:100%">';

					var fqContent = '<textarea class="form-control" cols="30" rows="10" name="fq_content[]" style="width:100%;height:50px;"></textarea>';

					var fqDelete = '<a href="javascript:void()" class="Delete1 btn btn-danger btn-xs">X</a>';

					trNew1 = trNew1 + '<tr> ';

					trNew1 += '<td>' + fqTitle + '</td>';
					trNew1 += '<td>' + fqContent + '</td>';
					trNew1 += '<td>' + fqDelete + '</td>';

					trNew1 = trNew1 + ' </tr>';

					$("#fqSection tbody").append(trNew1);

				});

				$('#fqSection').delegate('a.Delete1', 'click', function() {
					$(this).parent().parent().fadeOut('slow').remove();
					return false;
				});

			});


			$(document).ready(function() {

				$("#btnAddOpenningHour").click(function() {

					var trNew1 = "";

					var fqTitle = '<input autocomplete="off" type="text" class="form-control" name="oh_day[]" style="width:100%">';

					var fqContent = '<input autocomplete="off" type="text" class="form-control" name="oh_time[]" style="width:100%">';

					var fqDelete = '<a href="javascript:void()" class="Delete1 btn btn-danger btn-xs">X</a>';

					trNew1 = trNew1 + '<tr> ';

					trNew1 += '<td>' + fqTitle + '</td>';
					trNew1 += '<td>' + fqContent + '</td>';
					trNew1 += '<td>' + fqDelete + '</td>';

					trNew1 = trNew1 + ' </tr>';

					$("#ohSection tbody").append(trNew1);

				});

				$('#ohSection').delegate('a.Delete1', 'click', function() {
					$(this).parent().parent().fadeOut('slow').remove();
					return false;
				});

			});
		</script>
		<script>
			 $(document).ready(function(){
				$(".categories").change(function(){
					var $this = $(this);
					var cat_ids = $this.val();
					var Exist_products = $('#products').val();
					//alert(Exist_products);
					//var rowId = $this.data('row-id');
					var dataString = "cat_ids="+cat_ids+'&products='+Exist_products;
					console.log(dataString);
					$.ajax({
						type: "POST",
						data: dataString,
						url: "ajax/getAjaxData.php",
						success: function(result){
							$("#products").html(result);
						}
					});

				});
			});

			$(document).ready(function(){
				$(".productCats").change(function(){
					var $this = $(this);
					var cat_ids = $this.val();
					var Exist_sub_categories = $('#sub_categories').val();
					//alert(Exist_products);
					//var rowId = $this.data('row-id');
					var dataString = "product_cat_ids="+cat_ids+'&sub_categorie='+Exist_sub_categories;
					console.log(dataString);
					$.ajax({
						type: "POST",
						data: dataString,
						url: "ajax/getAjaxData.php",
						success: function(result){
							$("#sub_categories").html(result);
						}
					});

				});
			});

			$(document).ready(function() {
				$('.cat_id').on('change', function() {
					var catID = $(this).val();
					//alert(catID);
					if (catID) {
						$.ajax({
							type: 'POST',
							url: 'ajax/getAjaxData.php',
							data: 'cat_id=' + catID,
							success: function(html) {
								$('#subCategory').html(html);
							}
						});
					} else {
						$('#subCategory').html('<option value="">Select Category first</option>');
					}
				});
			});
		</script>

		<script>
			jQuery.validator.addMethod("lettersonly", function(value, element) {
				return this.optional(element) || /^[a-z\s]+$/i.test(value);
			}, "Only alphabetical characters");
			jQuery.validator.addMethod("numberonly", function(value, element) {
				return this.optional(element) || /^[0-9+()#\s]+(\-[0-9()\s]+)*$/i.test(value);
			}, "Only numbers are allowed");

			jQuery.validator.addMethod("tendigits", function(value, element) {
				if (value.replace(/[#-.()\+\s]/g, '').length < 10) {
					return false;
				} else {
					return true;
				}

			}, "minimun 10 digits required in phone numbers");


			jQuery.validator.addMethod("ninedigits", function(value, element) {
				if (value.replace(/[#-.()\+\s]/g, '').length < 9) {
					return false;
				} else {
					return true;
				}

			}, "minimun 9 digits required in amount")

			jQuery.validator.addMethod("validate_email", function(value, element) {
				if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
					return true;
				} else {
					return false;
				}
			}, "Please enter a valid Email.");

			jQuery("#register").validate({
				rules: {
					name: {
						required: true,
						lettersonly: true
					},

					email: {
						required: true,
						email: true,
						validate_email: true
					},

					password: {
						required: true,
						minlength: 5,
					},
					cnf_password: {
						required: true,
						minlength: 5,
						equalTo: "#pass"
					},
					status: {
						required: true,
					}
				},
				messages: {
					name: {
						required: "Please enter your name",
						lettersonly: "Invalid name. Only letters allowed"
					},
					email: {
						required: "Please enter your email address",
						email: "Please enter a valid email address",
						validate_email: "Please enter a valid email address"
					},

					password: {
						required: "Please enter your password",
					},
					cnf_password: {
						required: "Please enter your confirm password",
						equalTo: "Password mismatch"
					},
					status: {
						required: "Please select any status",

					}

				},
				submitHandler: function(form) {
					$(form).ajaxSubmit();
				}
			});
		</script>

		<script>
			// function changeStatus(leadId){
			// 	alert(leadId);
			// }

			function changeStatus(leadId) {
				var lead_status = $('#lead_status').val();
				var amout_sanction = $('#amout_sanction').val();
				var vendor_commission = $('#vendor_commission').val();
				alert(leadId + lead_status + amout_sanction + vendor_commission);
				if (leadId) {
					$.ajax({
						type: 'POST',
						url: 'getAjaxData.php',
						data: {
							lead_id: leadId,
							status: lead_status
						},
						success: function(html) {
							alert(html);
						}
					});
				}
			}
		</script>

		<script>
			function DoneDeal() {

				var lead_status = $('#lead_status').val();
				//alert(lead_status);
				if (lead_status == 'Done') {
					$('#amt_sanction').css("display", "block");
					//$('.amout_sanction').html('required');

					$('#amt_commission').css("display", "block");
					//$('.vendor_commission').html('required');
				} else {
					$('#amt_sanction').css("display", "none");
					$('#amt_commission').css("display", "none");
				}
			}
		</script>


		</body>

		</html>