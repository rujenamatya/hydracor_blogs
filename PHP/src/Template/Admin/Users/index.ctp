<?php ?>
    <div class="wrapper">
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height: 916px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo __('Administrator Management'); ?>
          </h1> 
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
			 
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo __('Administrator Tables'); ?></h3>
				  <?php
		         /*  if($loggeduser['User']['role'] !== "Admin" || $loggeduser['User']['role'] !== "User"){?>
				  <span style="float: right;">
				  <a href='<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'administrator_add')); ?>' class='btn bg-navy'><i class="fa fa-user"></i>&nbsp; <?php echo __('New Add'); ?></a>
				  </span>
				  <?php } */?>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><?php echo __('Type'); ?></th>
			            <th><?php echo __('Username'); ?></th>
			            <th><?php echo __('Action'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
					<?php foreach ($users as $user): ?>
				    <tr>
                        <td><?php echo ($user->role); ?></td>
					   <td><?php echo ($user->username); ?></td>
                      
					   <td>
					 
					   
					   <?php echo $this->Html->link(__("Edit"), array('action' => 'edit', $user->id),array('class'=>'btn btn-primary')); ?>
					   <?php if($user->adminis_disabled == 1){ ?>
					   <a href="javascript::void(0)" class="btn btn-default">Delete</a>
					   <?php }else{ ?>
			           <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user->id), array('class'=>'btn btn-danger'), __('Are you sure you want to delete', $user->id)); ?>
					   <?php } ?>
					   
					  </td>
				    </tr>
						<?php endforeach; ?>
                    </tbody>
                  </table>
                   </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div>


      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    <?php  ?>
    <style>
	.dataTable .btn.btn-default{cursor:auto;}
	</style>
