
	/* test log
	$dbuser = "root";
	$dbpasswd = "31138815";
	$sql = "INSERT INTO studio.log (content) VALUES ('auto');";
	$conn = mysql_connect('localhost', $dbuser, $dbpasswd);
	mysql_select_db('studio', $conn);
	mysql_query($sql) or die(mysql_error());
	*/
	
	
Yii technique
~~~~//_view.php
	<b><?php echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->student_id), array('view', 'id'=>$data->student_id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('nickname')); ?>:</b>
	<?php echo CHtml::encode($data->nickname); ?>
	<br />
	
	<b><?php echo CHtml::encode('Full Name'); ?>:</b>
	<?php echo CHtml::encode($data->last_name ." ". $data->first_name ." ". $data->chi_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gender_id')); ?>:</b>
	<?php echo CHtml::encode($data->gender->en); ?>
	<br />
~~~~

~~~~~//form from relational table 

create.php
<?php echo $this->renderPartial('_form', array(
'model'=>$model, 
'guardian'=>$guardian, 
'relationship'=>$relationship)); ?>

Student.php
search(){
	...
		/*
		$criteria->with=array('grade');
		$criteria->together= true;
		$criteria->compare('grade.grade_name', $this->grade_id, true);
		*/

		$criteria->join="LEFT JOIN grade gd ON t.grade_id = gd.grade_id ";
	...	
}

StudentController.php
	public function actionCreate()
	{
		$model=new Student;
		$guardian= new Guardian;
		$relationship = new Relationship;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Student']))
		{
			$model->attributes=$_POST['Student'];
			$valid=$model->validate();			

			if($valid)
			{
				//only try validate if there is input of guardian name
				if(($_POST['Guardian']['name'])!=NULL)
				{
					$guardian->attributes=$_POST['Guardian'];
					$relationship->attributes=$_POST['Relationship'];
					$valid_guardian=$guardian->validate() && $relationship->validate(array('relationship_type_id'));
					if($valid_guardian)
					{
						// use false parameter to disable validation
						$model->save(false);	
						$guardian->save(false);
						$relationship->student_id=$model->primaryKey;
						$relationship->guardian_id=$guardian->primaryKey;
						$relationship->save(false);
						$this->redirect(array('view','id'=>$model->student_id));		
					}
				} else {
					$model->save(false);	
					$this->redirect(array('view','id'=>$model->student_id)); 
				}
			}
		}
		$this->render('create',array(
			'model'=>$model,
			'guardian'=>$guardian,
			'relationship'=>$relationship,
		));
	}
~~~~~~

~~~~~~//form input method
_form.php
	<div class="row">
		<?php echo $form->labelEx($model,'gender_id'); ?>
		<? //php echo $form->textField($model,'gender_id'); ?>
		<?php echo $form->dropDownList($model, 'gender_id', CHtml::listData(Gender::model()->findAll(),'gender_id','abbr'), array('prompt'=>'- Choose -'));?>
		<?php echo $form->error($model,'gender_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'school_id'); ?>
		<? //php echo $form->textField($model,'school_id'); ?>
		<?php echo $form->dropDownList($model, 'school_id', CHtml::listData(School::model()->findAll(),'school_id','school_eng_name'), array('prompt'=>'- Choose -'));?>
		<?php echo $form->error($model,'school_id'); ?>
	</div>

	<div class="row">			
		<?php
		 $sql = "SELECT teacher_id, concat(first_name, ' ', last_name) as callname FROM teacher";
		 $cmd = Yii::app()->db->createCommand($sql);
		 $res = $cmd->queryAll();	
		?>
		<?php echo $form->labelEx($model,'teacher_id'); ?>
		<?php echo $form->dropDownList($model,'teacher_id', 
		CHtml::listData($res,'teacher_id','callname'),  array('prompt'=>'- Choose -'));?>
		<?php echo $form->error($model,'teacher_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'reg_date'); ?>
		<?php if($model->reg_date ==NULL){$model->reg_date=date("Y-m-d");} ?>
		<?php echo $form->textField($model,'reg_date'); ?>
		<?php echo $form->error($model,'reg_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiSlider', array(
		'value'=>50,
		'options'=>array(
			'min'=>0,
			'max'=>100,
			'slide'=>"js:function(event, ui) { $('#Lesson_number').val(ui.value);}"
		),
		'htmlOptions'=>array(
			'style'=>'height:12px;'
		),
		));?>
		
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'grade_id'); ?>
		<?//php echo $form->textField($model,'grade_id'); ?>
		<?php echo $form->dropDownList($model, 'grade_id', CHtml::listData(
		Yii::app()->db->createCommand()
		->select('grade_id, CONCAT(name, " ", grade_name) as name')
		->from('grade g, edu_system edu')
		->where('g.edu_system_id=edu.edu_system_id')
		->queryAll(),'grade_id','name'), array('prompt'=>'- Choose -'));?>
		<?php echo $form->error($model,'grade_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birth_date', array('label'=>'Date of Birth')); ?>
		<?//php echo $form->textField($model,'birth_date'); ?>
		<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', 
				array(
						'model' => $model,
						'attribute' => 'birth_date',
						'options' => array(
							'showAnim' => 'fold',
							'dateFormat' => 'yy-mm-dd', 
							'defaultDate' => $model->birth_date,
							'defaultDate' => '1990-01-01',
							'changeYear' => true,
							'changeMonth' => true,
							'yearRange' => '1900',
						),
                ));
		?>
		<?php echo $form->error($model,'birth_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($guardian,'mobile'); ?>
		<?php echo $form->textField($guardian,'mobile'); ?>
		<?php echo $form->error($guardian,'mobile'); ?>
	</div>	
	
~~~~~

~~~~~//CGrid View
		array(
			'header'=>'School',
			'name'=>'school_id',
			'filter'=>CHtml::listData(School::model()->findAll(),'school_id','school_eng_name'),
			'value'=>'$data->school->school_eng_name'
		),		
		//'grade_id',	
		//'grade.grade_name',
		array(
			'header'=>'Grade',
			'name'=>'grade_id',
			'type'=>'raw',
			'value'=>'$data->grade->grade_name',
		),		
		'mobile',
		'home_phone',
		
		/*
		array(
		'type'=>'raw',
		'value'=>'CHtml::link($data->school->school_eng_name, array("school/update", "id"=>$data->school->school_id));',
		'name'=>'school.school_eng_name',
		),*/
		
		
~~~~

~~~~//CDetailView
	'attributes'=>array(
		'student_id',
		array(
			'header'=>'Gender',
			'name'=>'gender_id',
			'value'=>CHtml::encode($model->gender->en)
		),
		array(
			'header'=>'School',
			'name'=>'school_id',
			'value'=>CHtml::encode($model->school->school_eng_name)
		),	
		array(
			'header'=>'Grade',
			'name'=>'grade_id',
			'value'=>CHtml::encode($model->grade->grade_name)
		),	
		'home_address',
		'remarks',
	),
~~~~~~

