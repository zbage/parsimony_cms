<?php
/**
 * Parsimony
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@parsimony.mobi so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Parsimony to newer
 * versions in the future. If you wish to customize Parsimony for your
 * needs please refer to http://www.parsimony.mobi for more information.
 *
 * @authors Julien Gras et Benoît Lorillot
 * @copyright  Julien Gras et Benoît Lorillot
 * @version  Release: 1.0
 * @category  Parsimony
 * @package core/fields
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$stamp = time();
?>
<script>
    function lead(val, length) {
        var val = val + '';
        while (val.length < length)  val = '0' + val;
        return val;
    }
    
    $(document).ready(function() {
  
        var myForm = $("#publishForm").closest("form");
            
        $(myForm).on('change','.datesql', function(e) { 
            var sqltime = lead($('.addyyyy', myForm).val(),4) + '-' + lead($('.addmm', myForm).val(),2) + '-' + lead($('.adddd', myForm).val(),2) + ' ' + lead($('.addhour', myForm).val(),2) + ':' + lead($('.addminut', myForm).val(),2) + ':' + lead($('.addsecond', myForm).val(),2);
            $('.datestatus', myForm).val(sqltime);           
        }); 
       
        $(myForm).on('change','.sticky input',function(){
            var idst = '';
            if($('input.stick', myForm).prop("checked") == false && $('input.public', myForm).prop("checked") == true) {
                idst = t('Public');
            }else if($('input.password', myForm).prop("checked") == true) idst = $('label.password', myForm).text();
            else idst = $('label.' + $(this).data("name"), myForm).text();
            $('.visibstatus', myForm).text(idst);
            var visibilitystatus = $(this).val();
            if($(this).hasClass('stick')) visibilitystatus = $('input.stick', myForm).val();     
            $('.visibilitystatus', myForm).val(visibilitystatus);
        }); 
        
        $(myForm).on('change','.sticky input[type=radio]',function(){
            $('input.stick').prop("checked", false);
            if($(this).hasClass('public')){
                $('input.stick', myForm).parent().show();
                $('input.passname', myForm).hide();
            }else if($(this).hasClass('password')){
                $('input.passname', myForm).show();
                $('.visibilitystatus', myForm).val($('input.passname', myForm).val());
            }else{
                $('input.stick', myForm).parent().hide();
                $('input.passname', myForm).hide();
            }
        });
       
        $(myForm).on('keyup','input.passname',function(){
            var str = $(this).val();
            if(!str || str.length === 0 && $('.visibilitystatus', myForm).val()== str){
                $('input.passname', myForm).attr('pattern','<?php echo $this->regex ?>').attr('required','');
            }
            $('.visibilitystatus', myForm).val(str);
        });
        
        $(myForm).on('click','.publish',function(){
            $('.publishcl', myForm).hide();
        });     
        
        $(myForm).on('click','.pubstatus input',function(){
            $('.pubstatus input', myForm).removeClass('active');
            $(this).addClass('active');
            var pub = $(this).data("ident");
            $('.pubstatuslabel', myForm).text(t(pub)); 
            if(pub == "Pending"){
                pub = t('Save as Pending');
                $('.publishstatus', myForm).val('2');
            }else if(pub == "Draft"){
                pub = t('Save Draft');
                $('.publishstatus', myForm).val('1');
            }else {
                pub = t('Publish');
                $('.publishstatus', myForm).val('0');
            }
            if($('input.stick', myForm).prop("checked") == false && $('input.public', myForm).prop("checked") == true) {
                $('.publishstatus', myForm).val('0');
            }
            $('input[name="add"]', myForm).val(pub);  
        });
        
        $('.public', myForm).trigger('click');
        $('.datesql', myForm).trigger('change');  
        $('.publish',myForm).trigger('click');
        $('.datesql',myForm).trigger('change');
        
        $(myForm).on('click','.slide',function(){
            $(this).next().slideToggle();
        });
    });
</script>
<style>
    .pubstatus input.active, .pubstatus input:hover{color: white;box-shadow: inset 0px 1px 2px #444;background-image: -webkit-gradient(linear,left bottom,left top,from(#959595),to(#555));}
    .pubstatus input {float: left;width: 30%;padding: 3px;cursor: pointer;border: 1px solid #555;text-align: center;background-image: -webkit-gradient(linear,left bottom,left top,from(#CCC),to(#F3F3F3));}
    .sticky{margin:2px 0 2px 5px;}
    .slide{cursor:pointer}
</style>
<div id="publishForm">
    <label for="<?php echo $this->name ?>">
        <?php echo t('Publish', False) ?>
        <?php if (!empty($this->text_help)): ?>
            <span class="tooltip ui-icon ui-icon-info" data-tooltip="<?php echo $this->text_help ?>"></span>
        <?php endif; ?>
    </label>
    <div class="slide"><span class="ui-icon ui-icon-arrowthickstop-1-s" style="display: inline-block;vertical-align: text-bottom;"></span><span style="font-weight: bold;"><?php echo t('Visibility', False) ?> :</span> <span class="visibstatus"></span></div>
    <div id="openvis" class="none">
        <div class="sticky">
            <input type="radio" name="<?php echo $this->name ?>_visibility" class="public" data-name="public" data-name="public"  value="0"><label for="visibility-public"class="public"><?php echo t('Public', False) ?></label>
        </div>
        <div class="sticky" style="padding-left: 15px;">
            <input class="stick" data-name="stick" type="checkbox" value="1">
            <label class="stick"><?php echo t('Stick this post to the front page', False) ?></label>
        </div>
        <div class="sticky hidestick">
            <input type="radio" name="<?php echo $this->name ?>_visibility" class="private" data-name="private" value="2"><label for="visibility-public" data-name="private" class="private"><?php echo t('Private', False) ?></label>
        </div>
        <div class="sticky hidestick">         
            <input type="radio" class="password" data-name="password" name="<?php echo $this->name ?>_visibility" value="3"><label for="visibility-public" class="password"><?php echo t('Password protected', False) ?></label>
            <input style="margin-top: 5px" class="none passname" type="text">
        </div>
    </div>    
    <input type="hidden" class="visibilitystatus" name="<?php echo $this->name ?>_visibility">

    <div style="padding: 2px 0 0" id="openstatus" class="slide"><span class="ui-icon ui-icon-arrowthickstop-1-s" style="display: inline-block;vertical-align: text-bottom;"></span><span style="font-weight: bold;" for="<?php echo $this->name ?>">Status :</span> <span class="pubstatuslabel"></span></div>
    <div id="openst" class="pubstatus none" style="margin: 5px 0">
        <input type="button" style="border-radius: 5px 0 0 5px;" value="<?php echo t('Pending', FALSE); ?>" data-ident="Pending">
        <input type="button" style="" value="<?php echo t('Draft', FALSE); ?>" data-ident="Draft">
        <input type="button" style="border-radius: 0 5px 5px 0;" value="<?php echo t('Publish', FALSE); ?>" data-ident="Publish" class="publish">
        <input type="hidden" value="" class="publishstatus" name="<?php echo $this->name . '_status' ?>">
    </div>
    <div style="clear: both;padding: 5px 0;min-width: 237px;" >
        <span style="font-weight: bold;"><?php echo t('Publish', false); ?>  <?php echo t('Immediately', false); ?></span><span style="padding-left:5px"><?php echo t('Or', false); ?></span>
        <span style="padding-left:5px" class="slide"><?php echo t('Edit Planning', false); ?>  <img src="<?php echo BASE_PATH ?>admin/img/calendar.gif" style="padding-left:5px;cursor:pointer"/></span>
        <div class="none publishcl" style="clear: both;font-size: 16px;color: #333;text-shadow: 0px 1px 0px white;padding-top: 8px;">
            <?php
            $locale = \app::$request->getLocale();
            $lang = '<input type="text" class="datesql adddd" style="width: 25px;" value="' . date('d', $stamp) . '" />';
            $m = date('m', $stamp);
            $select = ' <select type="text" class="datesql addmm" style="vertical-align: top;height: 28px;width: 70px;font-size: 13px;">';
            $month = array('01' => t('Jan', false), '02' => t('Feb', false), '03' => t('Mar', false), '04' => t('Apr', false), '05' => t('May', false), '06' => t('Jun', false), '07' => t('Jul', false), '08' => t('Aug', false), '09' => t('Sep', false), '10' => t('Oct', false), '11' => t('Nov', false), '12' => t('Dec', false));
            foreach ($month as $key => $month) {
                if ($key == $m)
                    $select .= '<option value="' . $key . '" selected="selected">' . $key . '-' . $month . '</option>';
                else
                    $select .= '<option value="' . $key . '">' . $key . '-' . $month . '</option>';
            }
            $select .='</select> ';
            if ($locale == 'fr_FR')
                echo $lang . $select;
            else
                echo $select . $lang . ',';
            ?>
            <input type="text" class="datesql addyyyy" style="width: 40px;" pattern="^[12][0-9]{3}$" value="<?php echo date('Y', $stamp); ?>" />
            @ <input class="datesql addhour" type="text" style="width: 25px;" pattern="(?:([01]?[0-9]|2[0-3]):)?([0-5][0-9])" value="<?php echo date('H', $stamp); ?>"> : 
            <input type="text" class="addminut datesql" style="width: 25px;" maxlength="2" pattern="(?:([01]?[0-9]|2[0-3]):)?([0-5][0-9])" value="<?php echo date('i', $stamp); ?>">
            <input type="hidden" class="addsecond datesql" pattern="(?:([01]?[0-9]|2[0-3]):)?([0-5][0-9])" value="<?php echo date('s', $stamp); ?>">
        </div>
        <input type="hidden" class="datestatus" name="<?php echo $this->name ?>">
    </div> 
</div>