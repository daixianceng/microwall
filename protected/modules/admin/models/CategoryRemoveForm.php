<?php
class CategoryRemoveForm extends RemoveAlertForm
{
	public $columnName = 'category';
	public $moveToMsg = 'Category not found.';
	
	public function getOtherList()
	{
		$categories = Category::getList();
		unset($categories[$this->id]);
		return $categories;
	}
}