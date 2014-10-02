<?php

/**
 * This is the model class for table "modx_site_content".
 *
 * The followings are the available columns in table 'modx_site_content':
 * @property integer $id
 * @property string $type
 * @property string $contentType
 * @property string $pagetitle
 * @property string $longtitle
 * @property string $description
 * @property string $alias
 * @property string $link_attributes
 * @property integer $published
 * @property integer $pub_date
 * @property integer $unpub_date
 * @property integer $parent
 * @property integer $isfolder
 * @property string $introtext
 * @property string $content
 * @property integer $richtext
 * @property integer $template
 * @property integer $menuindex
 * @property integer $searchable
 * @property integer $cacheable
 * @property integer $createdby
 * @property integer $createdon
 * @property integer $editedby
 * @property integer $editedon
 * @property integer $deleted
 * @property integer $deletedon
 * @property integer $deletedby
 * @property integer $publishedon
 * @property integer $publishedby
 * @property string $menutitle
 * @property integer $donthit
 * @property integer $haskeywords
 * @property integer $hasmetatags
 * @property integer $privateweb
 * @property integer $privatemgr
 * @property integer $content_dispo
 * @property integer $hidemenu
 */
class ModxSiteContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'modx_site_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('published, pub_date, unpub_date, parent, isfolder, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby, publishedon, publishedby, donthit, haskeywords, hasmetatags, privateweb, privatemgr, content_dispo, hidemenu', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>20),
			array('contentType', 'length', 'max'=>50),
			array('pagetitle, longtitle, description, alias, link_attributes, menutitle', 'length', 'max'=>255),
			array('introtext, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, contentType, pagetitle, longtitle, description, alias, link_attributes, published, pub_date, unpub_date, parent, isfolder, introtext, content, richtext, template, menuindex, searchable, cacheable, createdby, createdon, editedby, editedon, deleted, deletedon, deletedby, publishedon, publishedby, menutitle, donthit, haskeywords, hasmetatags, privateweb, privatemgr, content_dispo, hidemenu', 'safe', 'on'=>'search'),
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
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'contentType' => 'Content Type',
			'pagetitle' => 'Pagetitle',
			'longtitle' => 'Longtitle',
			'description' => 'Description',
			'alias' => 'Alias',
			'link_attributes' => 'Link Attributes',
			'published' => 'Published',
			'pub_date' => 'Pub Date',
			'unpub_date' => 'Unpub Date',
			'parent' => 'Parent',
			'isfolder' => 'Isfolder',
			'introtext' => 'Used to provide quick summary of the document',
			'content' => 'Content',
			'richtext' => 'Richtext',
			'template' => 'Template',
			'menuindex' => 'Menuindex',
			'searchable' => 'Searchable',
			'cacheable' => 'Cacheable',
			'createdby' => 'Createdby',
			'createdon' => 'Createdon',
			'editedby' => 'Editedby',
			'editedon' => 'Editedon',
			'deleted' => 'Deleted',
			'deletedon' => 'Deletedon',
			'deletedby' => 'Deletedby',
			'publishedon' => 'Publishedon',
			'publishedby' => 'Publishedby',
			'menutitle' => 'Menu title',
			'donthit' => 'Disable page hit count',
			'haskeywords' => 'has links to keywords',
			'hasmetatags' => 'has links to meta tags',
			'privateweb' => 'Private web document',
			'privatemgr' => 'Private manager document',
			'content_dispo' => '0-inline, 1-attachment',
			'hidemenu' => 'Hide document from menu',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('contentType',$this->contentType,true);
		$criteria->compare('pagetitle',$this->pagetitle,true);
		$criteria->compare('longtitle',$this->longtitle,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('link_attributes',$this->link_attributes,true);
		$criteria->compare('published',$this->published);
		$criteria->compare('pub_date',$this->pub_date);
		$criteria->compare('unpub_date',$this->unpub_date);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('isfolder',$this->isfolder);
		$criteria->compare('introtext',$this->introtext,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('richtext',$this->richtext);
		$criteria->compare('template',$this->template);
		$criteria->compare('menuindex',$this->menuindex);
		$criteria->compare('searchable',$this->searchable);
		$criteria->compare('cacheable',$this->cacheable);
		$criteria->compare('createdby',$this->createdby);
		$criteria->compare('createdon',$this->createdon);
		$criteria->compare('editedby',$this->editedby);
		$criteria->compare('editedon',$this->editedon);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('deletedon',$this->deletedon);
		$criteria->compare('deletedby',$this->deletedby);
		$criteria->compare('publishedon',$this->publishedon);
		$criteria->compare('publishedby',$this->publishedby);
		$criteria->compare('menutitle',$this->menutitle,true);
		$criteria->compare('donthit',$this->donthit);
		$criteria->compare('haskeywords',$this->haskeywords);
		$criteria->compare('hasmetatags',$this->hasmetatags);
		$criteria->compare('privateweb',$this->privateweb);
		$criteria->compare('privatemgr',$this->privatemgr);
		$criteria->compare('content_dispo',$this->content_dispo);
		$criteria->compare('hidemenu',$this->hidemenu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModxSiteContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
