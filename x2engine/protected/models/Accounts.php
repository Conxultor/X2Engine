<?php

/**
 * This is the model class for table "x2_accounts".
 *
 * The followings are the available columns in table 'x2_accounts':
 * @property integer $id
 * @property string $name
 * @property string $website
 * @property string $type
 * @property integer $annualRevenue
 * @property string $phone
 * @property string $tickerSymbol
 * @property integer $employees
 * @property string $assignedTo
 * @property integer $createDate
 * @property string $associatedContacts
 * @property string $description
 * @property integer $lastUpdated
 * @property string $updatedBy
 */
class Accounts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Accounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'x2_accounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('annualRevenue, employees, createDate, lastUpdated', 'numerical', 'integerOnly'=>true),
			array('name, website, phone', 'length', 'max'=>40),
			array('type', 'length', 'max'=>60),
			array('tickerSymbol', 'length', 'max'=>10),
			array('updatedBy', 'length', 'max'=>20),
			array('assignedTo, associatedContacts, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, website, type, annualRevenue, phone, tickerSymbol, employees, assignedTo, createDate, associatedContacts, description, lastUpdated, updatedBy', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
            
                $fields=Fields::model()->findAllByAttributes(array('modelName'=>'Accounts'));
                $arr=array();
                foreach($fields as $field){
                    $arr[$field->fieldName]=Yii::t('accounts',$field->attributeLabel);
                }
                
                return $arr;
                
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public static function getNames(){
		$arr=Accounts::model()->findAll();
		$names=array('0'=>'None');
		foreach($arr as $account){
			$names[$account->id]=$account->name;
		}
		return $names;
	}

	public static function parseUsers($arr){
		$str="";
		foreach($arr as $user){
			 $str.=$user.", ";
		}
		$str=substr($str,0,strlen($str)-2);
		return $str;
	}

	public static function parseUsersTwo($arr){
		$str="";
		foreach($arr as $user=>$name){
			$str.=$user.", ";
		}
		$str=substr($str,0,strlen($str)-2);
						
		return $str;
	}

	public static function parseContacts($arr){
		$str="";
		foreach($arr as $contact){
			$str.=$contact." ";
		}
		return $str;
	}

	public static function parseContactsTwo($arr){
		$str="";
		foreach($arr as $id=>$contact){
			$str.=$id." ";
		}
		return $str;
	}

	public static function editContactArray($arr, $model) {

		$pieces=explode(" ",$model->associatedContacts);
		unset($arr[0]);

		foreach($pieces as $contact){
			if(array_key_exists($contact,$arr)){
				unset($arr[$contact]);
			}
		}
		
		return $arr;
	}

	public static function editUserArray($arr, $model) {

		$pieces=explode(', ',$model->assignedTo);
		unset($arr['Anyone']);
		unset($arr['admin']);
		foreach($pieces as $user){
			if(array_key_exists($user,$arr)){
				unset($arr[$user]);
			}
		}
		return $arr;
	}

	public static function editUsersInverse($arr) {
		
		$data=array();
		
		foreach($arr as $username){
			$data[]=UserChild::model()->findByAttributes(array('username'=>$username));
		}
		
		$temp=array();
			foreach($data as $item){
				if(isset($item))
					$temp[$item->username]=$item->firstName.' '.$item->lastName;
			}
		return $temp;
	}

	public static function editContactsInverse($arr) {
		$data=array();
		
		foreach($arr as $id){
			if($id!='')
				$data[]=Contacts::model()->findByPk($id);
		}
		$temp=array();
		
		foreach($data as $item){
			$temp[$item->id]=$item->firstName.' '.$item->lastName;
		}
		return $temp;
	}

	public static function getAvailableContacts($accountId = 0) {
	
		$availableContacts = array();
		
		$criteria = new CDbCriteria;
		$criteria->addCondition("accountId='$accountId'");
		$criteria->addCondition(array("accountId=''"),'OR');
		
		
		$contactRecords = CActiveRecord::model('Contacts')->findAll($criteria);
		foreach($contactRecords as $record)
			$availableContacts[$record->id] = $record->name;

		return $availableContacts;
	}
		
	
	public static function getContacts($accountId) {
		$contacts = array();
		$contactRecords = CActiveRecord::model('Contacts')->findAllByAttributes(array('accountId'=>$accountId));
		if(!isset($contactRecords))
			return array();
		
		foreach($contactRecords as $record)
			$contacts[$record->id] = $record->name;
		
		return $contacts;
	}
	
	public static function setContacts($contactIds,$accountId) {
	
		$account = CActiveRecord::model('Accounts')->findByPk($accountId);
		
		if(!isset($account))
			return false;
		
		// get all contacts currently associated
		$oldContacts = CActiveRecord::model('Contacts')->findAllByAttributes(array('accountId'=>$accountId));
		foreach($oldContacts as $contact) {
			if(!in_array($contact->id,$contactIds)) {
				$contact->accountId = 0;
				$contact->company = '';		// dissociate if they are no longer in the list
				$contact->save();
			}
		}
		
		// now set association for all contacts in the list
		foreach($contactIds as $id) {
			$contactRecord = CActiveRecord::model('Contacts')->findByPk($id);
			$contactRecord->accountId = $account->id;
			$contactRecord->company = $account->name;
			$contactRecord->save();
		}
		return true;
	}
	
	
	public function behaviors() {
		return array(
			'ERememberFiltersBehavior' => array(
				'class' => 'application.components.ERememberFiltersBehavior',
				'defaults'=>array(),           /* optional line */
				'defaultStickOnClear'=>false   /* optional line */
			),
		);
	}

	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		// $criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		// $criteria->compare('website',$this->website,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('annualRevenue',$this->annualRevenue);
		$criteria->compare('phone',$this->phone,true);
		// $criteria->compare('tickerSymbol',$this->tickerSymbol,true);
		// $criteria->compare('employees',$this->employees);
		// $criteria->compare('assignedTo',$this->assignedTo,true);
		// $criteria->compare('createDate',$this->createDate);
		// $criteria->compare('associatedContacts',$this->associatedContacts,true);
		// $criteria->compare('description',$this->description,true);
		// $criteria->compare('lastUpdated',$this->lastUpdated);
		// $criteria->compare('updatedBy',$this->updatedBy,true);

		$dateRange = Yii::app()->controller->partialDateRange($this->createDate);
		if($dateRange !== false)
			$criteria->addCondition('createDate BETWEEN '.$dateRange[0].' AND '.$dateRange[1]);
			
		$dateRange = Yii::app()->controller->partialDateRange($this->lastUpdated);
		if($dateRange !== false)
			$criteria->addCondition('lastUpdated BETWEEN '.$dateRange[0].' AND '.$dateRange[1]);

		
		$dataProvider=new SmartDataProvider(get_class($this), array(
			'sort'=>array('defaultOrder'=>'name ASC'),
			'pagination'=>array(
				'pageSize'=>ProfileChild::getResultsPerPage(),
			),
			'criteria'=>$criteria,
		));
		$arr=$dataProvider->getData();
		foreach($arr as $account){
			$account->assignedTo=UserChild::getUserLinks($account->assignedTo);
			$account->associatedContacts=Contacts::getContactLinks($account->associatedContacts);
		}
		$dataProvider->setData($arr);

		return $dataProvider;
	}
}