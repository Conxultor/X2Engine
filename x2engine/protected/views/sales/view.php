<?php
/*********************************************************************************
 * The X2CRM by X2Engine Inc. is free software. It is released under the terms of 
 * the following BSD License.
 * http://www.opensource.org/licenses/BSD-3-Clause
 * 
 * X2Engine Inc.
 * P.O. Box 66752
 * Scotts Valley, California 95066 USA
 * 
 * Company website: http://www.x2engine.com 
 * Community and support website: http://www.x2community.com 
 * 
 * Copyright � 2011-2012 by X2Engine Inc. www.X2Engine.com
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * - Redistributions of source code must retain the above copyright notice, this 
 *   list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice, this 
 *   list of conditions and the following disclaimer in the documentation and/or 
 *   other materials provided with the distribution.
 * - Neither the name of X2Engine or X2CRM nor the names of its contributors may be 
 *   used to endorse or promote products derived from this software without 
 *   specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 ********************************************************************************/

$this->menu=array(
	array('label'=>Yii::t('sales','Sales List'), 'url'=>array('index')),
	array('label'=>Yii::t('sales','Create Sale'), 'url'=>array('create')),
	array('label'=>Yii::t('sales','View Sale')),
	array('label'=>Yii::t('sales','Update Sale'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('sales','Add A User'), 'url'=>array('addUser', 'id'=>$model->id)),
	array('label'=>Yii::t('sales','Add A Contact'), 'url'=>array('addContact', 'id'=>$model->id)),
	array('label'=>Yii::t('sales','Remove A User'), 'url'=>array('removeUser', 'id'=>$model->id)),
	array('label'=>Yii::t('sales','Remove A Contact'), 'url'=>array('removeContact', 'id'=>$model->id)),
	array('label'=>Yii::t('sales','Delete Sale'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);

?>

<h2><?php echo Yii::t('sales','Sale:'); ?> <b><?php echo $model->name; ?></b></h2>
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id'=>'contacts-form',
	'enableAjaxValidation'=>false,
	'action'=>array('saveChanges','id'=>$model->id),
));

$this->renderPartial('_detailView',array('model'=>$model,'form'=>$form,'currentWorkflow'=>$currentWorkflow));
$this->endWidget();

?>
<a class="x2-button" id="save-changes" href="#" onClick="submitForm('contacts-form');return false;"><span><?php echo Yii::t('app','Save Changes'); ?></span></a>
<a class="x2-button" href="#" onClick="toggleForm('#attachment-form',200);return false;"><span><?php echo Yii::t('app','Attach A File/Photo'); ?></span></a>
<a class="x2-button" href="shareSale/<?php echo $model->id;?>"><span><?php echo Yii::t('sales','Share Sale'); ?></span></a>
<br /><br />

<div id="attachment-form" style="display:none;">
	<?php $this->widget('Attachments',array('type'=>'sales','associationId'=>$model->id)); ?>
</div>
<?php

$this->widget('InlineActionForm',
	array(
		'associationType'=>'sales',
		'associationId'=>$model->id,
		'assignedTo'=>Yii::app()->user->getName(),
		'users'=>$users,
		'startHidden'=>false
	)
);

if(isset($_GET['history']))
    $history=$_GET['history'];
else
    $history="all";
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$actionHistory,
	'itemView'=>'../actions/_view',
	'htmlOptions'=>array('class'=>'action list-view'),
	'template'=> 
            ($history=='all'?'<h3>'.Yii::t('app','History')."</h3>":CHtml::link(Yii::t('app','History'),"?history=all")).
            " | ".($history=='actions'?'<h3>'.Yii::t('app','Actions')."</h3>":CHtml::link(Yii::t('app','Actions'),"?history=actions")).
            " | ".($history=='comments'?'<h3>'.Yii::t('app','Comments')."</h3>":CHtml::link(Yii::t('app','Comments'),"?history=comments")).
            " | ".($history=='attachments'?'<h3>'.Yii::t('app','Attachments')."</h3>":CHtml::link(Yii::t('app','Attachments'),"?history=attachments")).
            '</h3>{summary}{sorter}{items}{pager}',
));
?>