<?php ?>
<h3 style="color:#2d6f96;line-height:0px;">Edit</h3>
<div class="separator-breadcrumb border-top"></div></br>
<div class="well">
    <div class="padding-md bg-white" style="border-top:3px solid #d2d6de;">
        <div class="table-responsive1" id="add_client">
        <?= $this->Form->create($notification) ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= $this->Form->input('client_id', array('type'=>'select', 'options'=> $clients, 'label'=>'Clients','empty'=>'--Select--','class'=>'form-control','required'=>'required')); 
                            ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <?= $this->Form->input('role_id', array('type'=>'select', 'options'=>[], 'label'=>'Role','class'=>'form-control roleList','required'=>'required','multiple'=>true)); 
                            ?>
                        </div>
                    </div>

                
                       <div class="col-md-4">
                        <div class="form-group">
                            <?php 
                                $status = ['1' => 'Active','0' => 'Inactive'];
                                echo $this->Form->input('status', array('type'=>'select', 'options'=> $status, 'label'=>'Status', 'empty'=>'--Select--','class'=>'form-control','required'=>'required')); 
                            ?>
                        </div>
                    </div>

                        <div class="form-group col-md-4" ;"> 
                          <?php echo $this->Form->input('color_code',['class'=>'form-control text_value type_color','name' => 'color_code','type'=>'color']);?>
                      </div>


                    <div class="row">   
                    <div class="col-md-12">
                        <div class="form-group" style="float:right; margin-top: 25px;">
                            <?php 
                                echo $this->Form->button(__('Save'),['class'=>'btn btn-primary']); 
                            ?>
                        </div>
                    </div>
                    
                </div>
<script>
$(document).ready(function() {
    $('.roleList').multiselect({
      buttonClass: 'btn btn-default col-md-2',
      inheritClass: false,
      buttonWidth: '325px',
      includeSelectAllOption: true,
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
      numberDisplayed:1,
      maxHeight: 200
    });
    getClientRoles('','<?= $notification['client_id'] ?>','<?= $notification['role_id'] ?>');

    $('.gender_section').hide();
    if($('#genderAttachment').is(':checked')){
        $('.gender_section').show();
    }
}); 

var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

function getClientRoles(curr,clientId,roles){
   
    if (typeof clientId !== "undefined") {
        var clientId = clientId;
    }else{
        var clientId = $(curr).val();
    }


    $.ajax({
        type:'post',
        url: "<?= $this->Url->build(['action'=>'getClientRoles']); ?>",
        data:'client_id='+clientId+'&role_id='+roles,
        success:function(result){
            $(".roleList").multiselect('destroy');
            $('.roleList').html(result);
            $(".roleList").multiselect({
              buttonClass: 'btn btn-default col-md-2',
              inheritClass: false,
              buttonWidth: '325px',
              includeSelectAllOption: true,
              enableCaseInsensitiveFiltering: true,
              enableFiltering: true,
              numberDisplayed:1,
              maxHeight: 200
            });
        }
    });
    
}
</script>
        <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array('ui.jqgrid','jquery-ui','bootstrap-multiselect'));

echo $this->Html->script(['jquery.jqGrid.min','grid.locale-en','bootstrap-multiselect','prettify.min']);

echo $this->Html->script('jquery-ui.js');


?>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
