<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Add Library</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <form method="post" action="index.php?controller=association&action=processForm">
                <h2 class="form-title">Associantion</h2>
                
                <div class="form-body">
                    <div class="field_parent_block required<?php if(isset($formErrors['name'])): echo ' has-error'; endif;?>">
                        <label><!--<span class="required-span">*</span>--> nom de l'association:</label>
                        <div class="input_block">
                            <input type="text" name="name" value="<?php if(isset($submittedData['name'])): echo $submittedData['name']; endif;?>">
                            <span class="error"><?php if(isset($formErrors['name'])): echo $formErrors['name']; endif;?></span>
                        </div>
                    </div>
                    <div class="field_parent_block required<?php if(isset($formErrors['ownerName'])): echo ' has-error'; endif;?>">
                        <label>siege:</label>
                            <div class="input_block">
                                <input type="text" name="ownerName" value="<?php if(isset($submittedData['ownerName'])): echo $submittedData['ownerName']; endif;?>">
                                <span class="error"><?php if(isset($formErrors['ownerName'])): echo $formErrors['ownerName']; endif;?></span>
                            </div>
                    </div>
                    <div class="field_parent_block<?php if(isset($formErrors['status'])): echo ' has-error'; endif;?>">
                        <label>Status:</label>
                        <div class="input_block">
                            <select name="status">
                                <?php $selectedStatus = ''; if(isset($submittedData['status'])): $selectedStatus = $submittedData['status']; endif;?>
                                <option value="1" <?php if($selectedStatus == '1'): echo 'selected'; endif; ?>>Enabled</option>
                                <option value="0" <?php if($selectedStatus == '0'): echo 'selected'; endif; ?>>Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="field_parent_block required<?php if(isset($formErrors['addressName'])): echo ' has-error'; endif;?>">
                        <label>Address :</label>
                        <div class="input_block">
                            <input type="text" name="addressName" value="<?php if(isset($submittedData['addressName'])): echo $submittedData['addressName']; endif;?>">
                            <span class="error"><?php if(isset($formErrors['addressName'])): echo $formErrors['addressName']; endif;?></span>
                        </div>
                    </div>
                    <div class="field_parent_block required<?php if(isset($formErrors['telephone'])): echo ' has-error'; endif;?>">
                        <label>Telephone:</label>
                        <div class="input_block">
                            <input type="text" name="telephone"
                                   placeholder="(+333) 333-333-333" value="<?php if(isset($submittedData['telephone'])): echo $submittedData['telephone']; endif;?>">
                            <span class="error"><?php if(isset($formErrors['telephone'])): echo $formErrors['telephone']; endif;?></span>
                        </div>
                    </div>
                    <div class="field_parent_block required<?php if(isset($formErrors['neighborhood'])): echo ' has-error'; endif;?>">
                        <label>Devise:</label>
                        <div class="input_block">
                            <input type="text" name="neighborhood" value="<?php if(isset($submittedData['neighborhood'])): echo $submittedData['neighborhood']; endif;?>">
                            <span class="error"><?php if(isset($formErrors['neighborhood'])): echo $formErrors['neighborhood']; endif;?></span>
                        </div>
                    </div> 
                </div>
                <div class="form-footer">
                    <button class="btn btn-submit" type="submit">Ok</button>  
                    <a class="btn btn-cancel" href="index.php?controller=association">Cancel</a>
                </div>
            </form>
        </div>
    </body>
</html>