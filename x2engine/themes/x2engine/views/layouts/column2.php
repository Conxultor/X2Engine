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

$this->beginContent('/layouts/main'); ?>
<div class="container">
	<?php if(Yii::app()->controller->id!='admin' && Yii::app()->controller->id!='site' || Yii::app()->controller->action->id=='whatsNew'){ ?>
	<div class="span-4" id="sidebar-left-box">
		<?php }else{ ?>
	<div class="span-0">
		<?php } ?>
		<div id="sidebar-left">
		<!-- sidebar -->
		<?php 
			if(Yii::app()->controller->id!='admin' && Yii::app()->controller->id!='site' || Yii::app()->controller->action->id=='whatsNew'){
				$this->beginWidget('zii.widgets.CPortlet',array(
					'title'=>Yii::t('app','Actions'),
					'id'=>'actions'
				));
				$this->widget('zii.widgets.CMenu',array(
					'items'=>$this->menu,
				));
				$this->endWidget();

				$this->widget('TopContacts',array(
					'id'=>'top-contacts'
				));

				$this->widget('RecentItems',array(
					'currentAction'=>$this->getAction()->getId(),
					'id'=>'recent-items'
				));
			}
		?>
		</div>
	</div>
	<?php if(Yii::app()->controller->id!='admin' && Yii::app()->controller->id!='site' || Yii::app()->controller->action->id=='whatsNew'){ ?>
	<div class="span-15" id="content-box">
		<?php }else{ ?>
	<div class="span-19" id="content-box">
		<?php } ?>
		<div id="content">
		<!-- content -->
		<?php echo $content; ?>
		</div>
	</div>
	<div class="span-5 last" id="sidebar-right-box">
		<div id="sidebar-right">
		<?php
		
		$this->widget('SortableWidgets', array(
			//list of items
			'portlets'=>$this->portlets, 
			'jQueryOptions'=>array(
				'opacity'=>0.6,	//set the dragged object's opacity to 0.6
				'handle'=>'.portlet-decoration',	//specify tag to be used as handle
				'distance'=>20,
				'delay'=>150,
				'revert'=>50,
				'update'=>"js:function(){
					$.ajax({
							type: 'POST',
							url: '{$this->createUrl('site/widgetOrder')}',
							data: $(this).sortable('serialize'),
					});
				}"
			),
			/*
			'items'=>array(
				'id1'=>'Actions',
				'id2'=>'Skype',
				'id3'=>'Location',
				'id4'=>'Quick Charts',
				), * /
				// additional javascript options for the accordion plugin
				'jQueryOptions'=>array(
					'opacity'=>0.6,	//set the dragged object's opacity to 0.6
					'handle'=>'h3',	//specify tag to be used as handle
					'distance'=>20,
					'revert'=>50,
				)*/
			)
		);		

		
		//foreach($this->portlets as $class=>$properties)
			//$this->widget($class,$properties);
		?>
		</div>
		
	</div>
</div>
<?php $this->endContent(); ?>
