function addtocart(id){
	let proid = id;
	let comment_txt = $( '#comment-box-' + proid ).val();
	let quantity = $( '#quantity-' + proid ).val();       
    
	 $.ajax({
          type : "post",
          url :  aa_obj.ajaxurl,
          data : { action: "add_to_order", proid:proid, comment_txt:comment_txt, quantity:quantity },

          beforeSend:function(){
                let btnName = $( '#custom-order-btn-' + proid ).find('span').text('Adding...');
          },
          success: function(response) {              
               console.log('success');
               let btnName = $( '#custom-order-btn-' + proid ).find('span').text('Added');

               setTimeout(function(){
                    window.location.href = aa_obj.siteurl + '/cart/';
               }, 1500);
                         },
          error:function(){
               console.log('ajax not running.');
          }
     });
}

/* save comment on cart page */
function save_comment(id){

     let  proid = id;
     let comment_data = $('#pro-comment-box-'+proid).val();
     let pro_quantity = $('#pro_quantity-'+proid).val();     
     let user_id = $('.user-data').val();    

     $.ajax({
          type : "post",
          url :  aa_obj.ajaxurl,
          data : { action: "save_comments", proid:proid, comment_data:comment_data, user_id:user_id, pro_quantity:pro_quantity },

          beforeSend:function(){
               console.log('ajax start');
          },
          success: function(response) { 
               console.log(response); 
              window.location.href = aa_obj.siteurl + '/cart/';              
          },
          error:function(){
               console.log('ajax not running.');
          }
     });

}

$(document).ready(function(){
    
     $('.product-remove a').click(function(){
          let proid = $(this).data('product_id');
          let user_id = $(this).data('user_id');
          
          $.ajax({
               type : "post",
               url :  aa_obj.ajaxurl,
               data : { action: "remove_user_info", proid:proid, user_id:user_id },

               success: function(response) {
                    console.log('success');
               },
               error:function(){
                    console.log('ajax not running.');
               }
          });

     });

     /* open & close comment box on pro list page.*/

     $(".comment-add").click(function() {
         $(this).closest('.product-btns').siblings().show();
         $(this).hide();
     });

     $('.comment-cancel').click(function(){
          $(this).parents('.product-form').hide();
          $(this).parents('.product-form').siblings('.product-btns').find('.comment-add').show();
     });

     $("#new-user-btn").click(function(){

          $("#login-1").addClass("d-none");
          $("#new-user").removeClass("d-none");

     });

      $(".login-content .modal-close").click(function(){

          $("#new-user").addClass("d-none");
          $("#login-1").removeClass("d-none");

     });
});






