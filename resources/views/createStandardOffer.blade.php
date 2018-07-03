<?php
   /*  File Name  : standardOffer.php
    *  Description : Standard Offer Form
    *  Author      : Himanshu Singh  Date: 25th,Nov,2010  Creation
   */
   header('Content-Type: text/html; charset=utf-8');
   //echo $_SESSION['userid'];
   $userId = \Auth::user()->userid;
   $lang = 'ENG';

   if (isset($_POST['continue'])) {
//       $standardObj->saveNewStandardOffersDetails();
   }

   $menu = "offer";
   $offer = 'class="selected"';
   $showstandard = 'checked="checked"';

   if (isset($_GET['reedit'])) {
       $lang = $_SESSION['post']['lang'];
   }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link rel="stylesheet" href="client/css/stylesheet123.css" type="text/css">
<link rel="stylesheet" href="client/css/datePicker.css" type="text/css">
<script language="JavaScript" src="client/js/date.js" type="text/javascript"></script>
<script language="JavaScript" src="client/js/jquery.bgiframe.js" type="text/javascript"></script>
<script language="JavaScript" src="client/js/jquery.datePicker.js" type="text/javascript"></script>
<script language="JavaScript" src="client/js/ajaxuploadStand.js" type="text/javascript"></script>
<script language="JavaScript" src="client/js/jsStandardOffer.js" type="text/javascript"></script>
<!--<link rel="stylesheet" type="text/css" href="lib/vtip/css/vtip.css" />-->
<style type="text/css">
   img {
       border: 0
   }
   .center {
       width:900px;
    margin-left:auto;
    margin-right:auto;
   }
</style>
<body>
   <div class="center">
      <div id="msg" align="center">

      </div>

      <form name="register" action="{{ url('kitchen/saveNewStandardOffersDetails') }}" id="registerform" method="Post" enctype="multipart/form-data">
         <input type="hidden" name="preview" value="1">
         <input type="hidden" name="m" value="saveNewStandard">
         <div>
            <div id="preview_frame"></div>
         </div>
         <div id="msg" align="center">

         </div>
         <div id="main">
            <div id="mainbutton">
            <table>
            <tr>
               <table width="100%" cellspacing="0" border="0">
                  <tr>
                     <td align="center" class="blackbutton">Add  Dish</td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td>&nbsp;</td>
                           </tr>
                           <tr>
                              <td height="53" align="center"  class="redwhitebutton_small" style="padding-top:5px; text-align:center ">Add Dish</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td height="53">
                        <table width="100%" BORDER=0 cellpadding="0"  cellspacing="15">
                           <tr>
                              <td width="515" align="left" valign="top" class="inner_grid">Language:</td>
                              <td width="469"  align="left" valign="top">
                                 <select style="width:406px; background-color:#e4e3dd;" onChange="getLangImage(this.value);" class="text_field_new" name="lang" id="lang" >
                                    <option <?php if ($lang == "SWE")echo "selected='selected'"; ?> value="SWE">Swedish</option>
                                    <option <?php if ($lang == "ENG")echo "selected='selected'"; ?> value="ENG">English</option>
                                 </select>
                                 <div id='error_langStand' class="error"></div>
                              </td>
                              <td align="right" valign="middle"><a title="123" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>
                           <tr>
                              <td align="left" valign="top"  class="inner_grid">Dish Name<span class='mandatory'>*</span>:<br>
                              </td>
                              <td align="left" valign="top" >
                                 <INPUT class="text_field_new" type=text name="titleSloganStand" id="titleSloganStand" maxlength="50" onBlur="iconPreview(this.form); getTitleForProduct(this.form);standardPreview(this.form);" value="<?=isset($_SESSION['post']['titleSloganStand']) ? $_SESSION['post']['titleSloganStand'] : ''
                                    ?>">
                                 <div id='error_titleSloganStand' class="error" ></div>
                              </td>
                              <td align="right" valign="middle" ><a title="STITLE_TEXT" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>

                           <tr>
                              <td align="left" valign="top"  class="inner_grid">Description. Max. 50 characters<span class='mandatory'>*</span>:<br>
                              </td>
                              <td align="left" valign="top" >
                                 <INPUT class="text_field_new" type=text name="productDescription" id="productDescription" maxlength="150" onBlur="iconPreview(this.form);" value="<?=isset($_SESSION['post']['productDescription']) ? $_SESSION['post']['productDescription'] : ''
                                    ?>">
                                 <div id='error_productDescription' class="error" ></div>
                              </td>
                              <td align="right" valign="middle" ><a title="STITLE_TEXT" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>

                           <tr>
                              <td height="42" align="left">Dish Preparation Time<span class='mandatory'>*</span>:</td>
                              <td>
                                 <select class="text_field_new" style="background-color:#e4e3dd; width:406px; height:36px;border: 1px solid #abadb3;" tabindex="27" id="preparationTime" name="preparationTime">
                                    <option value="00:05:00">5 Minutes</option>
                                    <option value="00:10:00">10 Minutes</option>
                                    <option value="00:15:00">15 Minutes</option>
                                    <option value="00:20:00">20 Minutes</option>
                                    <option value="00:25:00">25 Minutes</option>
                                    <option value="00:30:00">30 Minutes</option>
                                    <option value="00:35:00">35 Minutes</option>
                                    <option value="00:40:00">40 Minutes</option>
                                    <option value="00:45:00">45 Minutes</option>
                                    <option value="00:50:00">50 Minutes</option>
                                    <option value="00:55:00">55 Minutes</option>
                                    <option value="00:59:00">59 Minutes</option>
                                 </select>
                              </td>
                           </tr>

                           <tr style="display:none;"> </tr>
                           <tr style="display:none;">
                              <td align="left" valign="top" class="inner_grid">Price(with currency):</td>
                              <td align="left" valign="top"><INPUT class="text_field_new" type=text name="price" id="price"></td>
                              <td align="right" valign="middle"><a title="PRICE_TEXT" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>
                           <tr>
                              <td align="left" valign="top" class="inner_grid" style="line-height:25px;">Small icon <font size="2">(Icon must be in png or jpg format only e.g. icon.png.The size must be at least 45 x 60 pixels)</font></td>
                              <td align="left" valign="top">
                                 <div id="pre_image">
                                    <?php if (isset($_SESSION['preview']['small_image'])) {
                                       ?>
                                    <input class="text_field_new" type="hidden" name="smallimage" id="smallimage" value="<?=$_SESSION['preview']['small_image'] ?>">
                                    <br>
                                    <?php
                                       }
                                       ?>
                                 </div>
                                 <INPUT class="text_field_new" type=file name="icon" id="icon" onBlur="iconPreview(this.form);" >
                                 <div id='error_icon' class="error"></div>
                                 <div>
                                    <input class="text_field_new" type="hidden" id="selected_image" name="selected_image" value="0">
                                 </div>
                              </td>
                              <td align="right" valign="top"><a title="SICON_TEXT" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>
                           <tr style="display:none;">
                              <td colspan="5" align="center" height="20"><strong>
                                 <button onClick="ajaxUpload(this.form,'classes/ajx/ajxUpload.php?filename=icon&amp;maxW=200','upload_area'); return false;">Click here</button>
                                 to check how your short standard offer proposal looks like</strong>
                              </td>
                           </tr>
                        </table>
                        <table  border="0" align="center" cellpadding="0" cellspacing="0">
                           <tr id="short_preview" style="display:inline;">
                              <td width="422" align="center" valign="top" style="background-image:url(client/images/iphone_large-3.png); width:270px; height:559px; background-repeat:no-repeat;">
                                 <div style="margin-top:80px; width:225px; margin-left:5px; margin-right:auto;" >
                                    <table border="0" cellpadding="0" cellspacing="0">
                                       <tr>
                                          <td width="41"  align="left" style="padding-left:5px; padding-right:5px;">
                                             <div id="upload_area" style="vertical-align:top;"><img src=""  height = 30 width = 50 id="myCatIcon" name="myCatIcon"></div>
                                          </td>
                                          <td rowspan="2" valign="top">
                                             <table width="98%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                   <td class="mob_title_2" id="tslogan"></td>
                                                   <td width="21" align="right" nowrap style="padding-right:3px;">
                                                      <div><font size="-3"></font></div>
                                                   </td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
                              </td>
                           </tr>
                        </table>
                        <br>
                        <div class="redwhitebutton_small123" style="display:none;">Describe how your Standard Offer should Behave</div>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="display:none;">
                           <tr>
                              <td width="50%" valign="top" class="td_pad_left">Sponsored Standard Offer<span class='mandatory'>*</span>:</td>
                              <td width="50%" class="td_pad_right">
                                 <select style="width:406px; background-color:#e4e3dd; border:#abadb3 solid 1px;" class="text_field_new"  tabindex="27" id="sponsStand"
                                    name="sponsStand">
                                    <option <?php if (isset($_SESSION['post']['sponsStand']) == '0'
                                       )echo "selected='selected'"; ?> value="0">No</option>
                                    <option <?php if (isset($_SESSION['post']['sponsStand']) == '1'
                                       )echo "selected='selected'"; ?> value="1">Yes</option>
                                 </select>
                                 <br>
                                 <span style="font-size:12px;"> (Price per view 0.01 kr)</span>
                                 <div id='error_sponsStand' class="error"></div>
                              </td>
                           </tr>
                        </table>
                        <div class="redwhitebutton_small123"><span style="cursor:pointer;" onClick="showAdvancedSearchStand();">Advanced Options-Optional</span></div>
                        <table border="0" width="100%">
                        </table>
                        <table width="100%" BORDER=0 cellpadding="0" cellspacing="0"  >
                           <tr>
                              <td width="50%" align="left" valign="top" class="td_pad_left">Keyword<span class='mandatory'>*</span></td>
                              <td width="50%" align="left" valign="top" class="td_pad_right">
                                 <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td>
                                          <INPUT class="text_field_new" type=text name="searchKeywordStand" id="searchKeywordStand" maxlength="90">                             
                                          <div id='error_searchKeywordStand' class="error" ></div>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                              <td align="right" valign="middle"><a title="SKEYWORD_TEXT" class="vtip"><b><small>?</small></b></a> </td>
                           </tr>

                        </table>
               </table>
               </tr>
               <tr>
                  <td >
                     <div class="redwhitebutton_small123">Add your Coupon View</div>
                     <table width="100%" border="0">
                        <tr>
                           <td width="100%">
                              <table width="100%" BORDER=0 cellpadding="0" cellspacing="0" >


                                 <tr>
                                    <?php
                                       $d = date("Y/m/d");
                                        ?>
                                    <td width="50%" align="left" valign="top" class="td_pad_left">Release date of product<span class='mandatory'>*</span>:</td>
                                    <td width="50%" align="left" valign="top" class="td_pad_right">
                                       <table border="0" align="left" cellpadding="0" cellspacing="0">
                                          <tr>
                                             <td><input type="text" name="startDateStand" readonly="readonly" value="<?php echo $d;
                                                ?>" id="startDateStand" class="startDateStand dp-applied text_field_new123" /></td>
                                             <td style="padding-left:10px;"><a title="RELEASE_DATE_OF_PRODUCT" class="vtip"><b><small>?</small></b></a></td>
                                          </tr>
                                       </table>
                                       <div id='error_startDateStand' class="error"></div>
                                    </td>
                                 </tr>
                                  <tr>
                                    <td width="50%" align="left" valign="top" class="td_pad_left"><p>Type of Dish<span class='mandatory'>*</span></p><a style="font-size: 15px;vertical-align: top; cursor:pointer; text-decoration: underline;" id="add_tpye_of_dish">Add New Tpye Of Dish</a>:</td>
                                    <td width="50%" align="left" valign="top" class="td_pad_right">
                                      <?php $value = 0; ?>
                                      <div class="adddishes">
                                          <select id= "xx" name="select2" style="width:406px; background-color:#e4e3dd; border:#abadb3 solid 1px;" class="text_field_new" >
                                                      <option value="68">Veg</option>
                                          </select>
                                          <div id='error_startDateStand' class="error"></div>
                                      </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="4">
                                       <INPUT class="text_field_new" type="hidden" id="productName" name="productName" value="" >
                                       <div id='error_productName' class="error"></div>
                                    </td>
                                 </tr>

                              </table>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td>&nbsp;</td>
               </tr>

               </table>
            </div>
            <div align="center"> <br />
               <br />
               {{ csrf_field() }}
               <INPUT type="submit" value="Continue" name="continue" id="continue" class="button" >
               <br />
               <br />
            </div>
            <span class='mandatory'>* These Fields Are Mandatory</span> 
         </div>
      </form>
   </div>
   <div id="addDishType-popup" style="display: none;" class="login-popup" data-theme="a">
     <div class="inner-popup">
           <div id = "cancel-popup" class="cross">
              <img src="client/images/cross.png" />
           </div>
           <div class="pop-body">
              <div class="form-group">
                 <label>Language :</label>
                 <select id = "txtDishLanguage">
                     <option value="ENG">English</option>
                     <option value="SWE">Swedish</option>
                 </select>
              </div>
              <div style="display: none;">
                   <select id = "Userdetail">
                     <option value = <?php echo $userId ?> ></option>
                 </select>
              </div>

               <div class="form-group">
                 <label>Type Of Dish :</label>
                 <input id="txtDishType" type="text" />
              </div>
              <div class="form-group">
                 <input type="submit" value="Continue" name="continue" id="submit-btn" class="form-submit-btn">
               </div>
           </div>
     </div>
   </div>
</body>

<script type="text/javascript">
   $("#icon").change(function() {
     readURL(this);
   });

   function readURL(input) {

   if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function(e) {
         $('#myCatIcon').attr('src', e.target.result);
       }

       reader.readAsDataURL(input.files[0]);
     }
   }

   $('#add_tpye_of_dish').click(function(){
      $('#addDishType-popup').show();
   });

   $("#cancel-popup").click(function () {
       $('#addDishType-popup').hide();
     });

   $(function(){
      $('[id*=submit-btn]').click(function(){
         var dataString = 'Languang='+$('[id*=txtDishLanguage]').val()+'&DishType='+$('[id*=txtDishType]').val()+'&userId='+$('[id*=Userdetail]').val();
         console.log(dataString);
         $.ajax({
            type: "POST",
            url: "saveAction.php?",
            data: dataString,
            success: function (response) {
               $data = JSON.parse(response);
               //console.log($data);

               var str = ""

               for(var i =0;i<$data.length;i++){
                  console.log($data[i]["dish_name"]);
               str += "<option value="+$data[i]["dish_id"]+">"+$data[i]["dish_name"]+"</option>";
               }

               str += "<option value='addNewDishTpye'> Add Type Of Dish </option>";

               $("#xx").html(str);

               $('#addDishType-popup').hide();

            },
            failure: function (response) {
               alert(response.responseText);
            },
            error: function (response) {
               alert(response.responseText);
            }
         });
      });
   });
</script>

<script language="JavaScript" src="client/js/jsImagePreview.js" type="text/javascript"></script>

<style type="text/css">
   .login-popup {
    background: rgba(0, 0, 0, 0.25);
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}
.login-popup .inner-popup {
    width: 500px;
    background: #fff;
    top: 30%;
    position: absolute;
    left: 50%;
    transform: translate(-50% , -50%);
    border-radius: 10px;
    padding: 15px;
}
.cross {
    position: absolute;
    top: -5px;
    right: -5px;
}
.cross img {
    width: 18px
}

.login-popup .inner-popup p {
    margin: 0;
    padding: 0;
}
.login-popup .inner-popup .form-group{
   margin-bottom: 10px;
}
.login-popup .inner-popup label{
   display: inline-block; width: 40%; font-size: 14px; 
}
.login-popup .inner-popup select, .login-popup .inner-popup input[type="text"]{
   width: 40%;
   background: #efefefc4;
   height: 30px;
    border: 1px solid #ccc;
}
.login-popup .inner-popup input{
   padding-left: 5px; box-sizing: border-box;
}
.login-popup  .pop-body{
   margin-top: 10px;
}
input.form-submit-btn{
   color: #FFFFFF;
    border: none;
    font-size: 18px;
    background:#721a1e;
    width: 151px;
    height: 41px;
    background-repeat: no-repeat;
    font-weight: bold;
    cursor: pointer;
    display: block;
    margin: 25px auto 0;
    border-radius: 10px;
}
</style>
