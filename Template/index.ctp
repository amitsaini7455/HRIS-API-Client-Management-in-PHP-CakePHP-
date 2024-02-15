<div class="row margin-bottom-md ams">
    <div class="col-md-6">
      <h3 style="color:#2d6f96;line-height:0px;">Notification</h3>
    </div>
    <div class="col-sm-2" style="float:right;"> 
        <?php echo $this->Html->link(__('Add', true), array('action' => 'add'), array('class' => 'pull-right btn btn-raised btn-block btn-primary btn-xs')); ?> 
    </div>
</div>
<div class="separator-breadcrumb border-top"></div><br/>
<div class="well">
<div class="padding-md bg-white" style="border-top:3px solid #d2d6de;">
<div class="table-responsive" id="dep_master">
    <table class="table table-bordered table-striped results">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Module</th>
                <th scope="col">Default Male</th>
                <th scope="col">Default Female</th>
                <th scope="col">Image</th>
                <th scope="col">Created On</th>
                <th scope="col">Updated On</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hrisNotification as $notification){ ?>
            <tr>
                <td><?= $notification['clientName'] ?></td>

               <td><?php foreach (explode(",", $notification['module']) as $key => $module) {
                   echo $moduleArray[$module]."<br>";
               } ?> </td>

               <td>
                <?php if(!empty($notification['default_male'])){ ?>
                    <img src="<?=$uploadPath.$notification['default_male']  ?>" width="70" height="50"></td>
               <?php  } ?>

               <td> 
                    <?php if(!empty($notification['default_female'])){ ?>
                    <img src="<?=$uploadPath.$notification['default_female']  ?>" width="70" height="50"></td>
                   <?php  } ?>
               </td>
              <td>
    <?php if (!empty($notification['imagePath'])): ?>
        <img src="<?=$uploadPath.$notification['imagePath']  ?>" width="70" height="50">
    <?php endif; ?>
</td>


                  <td><?= (($notification['created_on'])) ?></td> 
              <td><?= (($notification['updated_on'])) ?></td> 
                   <td>
                    <a href="javascript:void(0);" data-id="<?= $notification['id'] ?>" data-status="<?= $notification['status']?>" onclick="return changeStatus(this);">
                        <?php if($notification['status'] == 1) {
                                $classAttendance = 'success';
                                $textAttendance = 'Active';
                            } else {
                                $classAttendance = 'danger';
                                $textAttendance = 'Inactive';
                            }
                         ?>
                        <span class="label label-<?= $classAttendance ?>" title="<?= $textAttendance ?>"><?= $textAttendance ?></span>
                    </a>
                </td>
                
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $notification['id']]) ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>
<script>
    $(document).ready(function() {
       $('table').DataTable();
   });
</script>

<script>
    function changeStatus(curr){
        var id = $(curr).attr("data-id");
        var currStatus = $(curr).attr("data-status");

        var baseurl = '<?php echo $baseurl?>';
        var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
        if(confirm('Do you want to change the status? ')) {
            $.ajax({  
                type: "POST",
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url:"<?php echo $this->Url->build(['action'=>'statusChange']); ?>",
                data: { 'id': id},      
                success: function(res){    
                    if(res == 'success'){
                        if(currStatus == 1){
                            $(curr).html('<span class="label label-danger" title="Inactive">Inactive</span>');
                            $(curr).attr('data-status',0);
                        }else{
                            $(curr).html('<span class="label label-success" title="Active">Active</span>');
                            $(curr).attr('data-status',1);
                        }
                    }else{
                        return false;
                    }   
                }
            });
        }else{
            return false;
        }
    }   
</script>

<?php
echo $this->Html->script('datatables/jquery.dataTables.min.js'); 
?>