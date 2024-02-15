<?php ?>
<h3 style="color:#2d6f96;line-height:0px;">Edit</h3>
<div class="separator-breadcrumb border-top"></div></br>
<div class="well">
    <div class="padding-md bg-white" style="border-top:3px solid #d2d6de;">
        <div class="table-responsive1" id="add_client">
        <?= $this->Form->create($notification,['type' => 'file']) ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= $this->Form->input('client_id', array('type'=>'select', 'options'=> $clients, 'label'=>'Clients','empty'=>'--Select--','class'=>'form-control','required'=>'required')); 
                            ?>
                        </div>
                    </div>
                
                    <div class="col-md-4">
                  <div class="form-group">
                  <?= $this->Form->input('module', array('type'=>'select','id'=>'module', 'options'=> $moduleArray, 'label'=>'Module','multiple'=>true,'class'=>'form-control moduleList','onchange'=>'handleModuleChange()','value'=>explode(",", $notification['module']))); ?>
                  </div>
                     </div>

                  <div class="col-md-4">
                      <div class="form-group">
                          <?= $this->Form->input('default_male', array('type' => 'file','label' => 'Default Male', 'class' => 'form-control')); ?>
                      </div>
                  </div>

                  <div class="col-md-4">
                      <div class="form-group">
                          <?= $this->Form->input('default_female', array('type' => 'file','label' => 'Default Female', 'class' => 'form-control')); ?>
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

                    <div class="col-md-4">
                <div class="form-group">
                <div id="image-field-container">
                    <?= $this->Form->input('file_path', array('type' => 'file','label' => 'Image', 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
    <div class="form-group">
        <div id="image-field-container">
            <?= $this->Form->input('file_path', ['type' => 'file', 'label' => 'Image', 'class' => 'form-control']) ?>
            <?php
            if (!empty($notification['file_path'])) {
                echo $this->Html->link('View', $uploadPath . $notification['file_path'], ['target' => '_blank']);
                echo $this->Html->image($uploadPath . $notification['file_path'], ['class' => 'img-thumbnail']);
            }
            ?>
        </div>
    </div>
</div> -->

                    
                    <!-- <div class="col-md-4">
                      <div class="form-group">
                         <label></label>
                          <div id="image-field-container">
                      </div>
                    </div>
                   </div> -->

                    <div class="row">   
                    <div class="col-md-12">
                        <div class="form-group" style="float:right; margin-top: 25px;">
                            <?php 
                                echo $this->Form->button(__('Save'),['class'=>'btn btn-primary']); 
                            ?>
                        </div>
                    </div>
                    
                </div>
        <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
  $('.moduleList').multiselect({
      buttonClass: 'btn btn-default col-md-2',
      inheritClass: false,
      buttonWidth: '325px',
      includeSelectAllOption: true,
      enableCaseInsensitiveFiltering: true,
      enableFiltering: true,
      numberDisplayed:1,
      maxHeight: 200
  });
}); 
 
    $('#module').on('change');
    function handleModuleChange() {
      var moduleSelect = document.getElementById('module');
      var imageFieldContainer = document.getElementById('image-field-container');

       if (moduleSelect.value !== '') {
                imageFieldContainer.style.display = 'block';
            } else {
                imageFieldContainer.style.display = 'none';
            }

      var moduleArray = <?= json_encode($moduleArray) ?>;
      var selectedOptions = Array.from(moduleSelect.selectedOptions);
      imageFieldContainer.innerHTML = '';

      selectedOptions.forEach(function(option) {
        var optionValue = option.value;
        var optionLabel = moduleArray[optionValue];

        var imageFieldLabel = document.createElement('label');
        imageFieldLabel.textContent = 'Image for ' + optionLabel;

        var imageField = document.createElement('input');
        imageField.type = 'file';
        imageField.name = 'file_path';
        imageField.accept = 'image/*';

        imageFieldContainer.appendChild(imageFieldLabel);
        imageFieldContainer.appendChild(imageField);
      });
    }
    $('#module').on('change', handleModuleChange);
  
</script>
<?php
echo $this->Html->css(array('ui.jqgrid','jquery-ui','bootstrap-multiselect'));

echo $this->Html->script(['jquery.jqGrid.min','grid.locale-en','bootstrap-multiselect','prettify.min']);

echo $this->Html->script('jquery-ui.js');


?>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
