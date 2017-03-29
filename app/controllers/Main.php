<?php

	class Main extends Controller {
		
		public function index($entityName, $operation) {
			$this->model('AtomDataControl');
			$atomDataControl = new AtomDataControl();
			$entity = $atomDataControl->getEntityObjectByName($entityName);
			$this->model('AtomConstants');
			$atomConstants = new AtomConstants();
			switch ($operation) {
				case 'list':
					$this->view('main/ListView', ['atomConstants' => $atomConstants, 'entity' => $entity]);
					break;
				case 'detail':
					$this->view('main/DetailView', ['atomConstants' => $atomConstants, 'entity' => $entity]);
					break;
				case 'add':
					$this->view('main/AddView', ['atomConstants' => $atomConstants, 'entity' => $entity]);
					break;
				case 'addprocess':
					$this->addData($entity);
					break;
				case 'delete':
					$this->removeData($entity);
					break;
				case 'edit':
					$this->view('main/EditView', ['atomConstants' => $atomConstants, 'entity' => $entity]);
					break;
				case 'editprocess':
					$this->editData($entity);
					break;
			}
		}
		
		public function addData($entity) {
			$fieldNameArray = [];
			$valueArray = [];
			foreach ($entity->getFieldObjectArray() as $fieldObject) {
				//FieldTypeMapping
				if ($fieldObject->getFieldType() == 'string') {
					array_push($fieldNameArray, $fieldObject->getName());
					array_push($valueArray, "\"".$_POST[$fieldObject->getName()]."\"");
				}
				else if ($fieldObject->getFieldType() == 'number') {
					array_push($fieldNameArray, $fieldObject->getName());
					array_push($valueArray, $_POST[$fieldObject->getName()]);
				}
				else if ($fieldObject->getFieldType() == 'reference') {
					array_push($fieldNameArray, $fieldObject->getName().'_id');
					array_push($valueArray, $_POST[$fieldObject->getName()]);
				}
			}
			if ($entity->addData($fieldNameArray, $valueArray)) {
				header('Location: list?alert=addsuccess');
			}
			else {
				header('Location: list?alert=addfailed');
			}
		}
		
		public function removeData($entity) {
			if ($entity->removeData($_GET['id'])) {
				header('Location: list?alert=deletesuccess');
			}
			else {
				header('Location: list?alert=deletefailed');
			}
		}
		
		public function editData($entity) {
			$fieldNameArray = [];
			$valueArray = [];
			foreach ($entity->getFieldObjectArray() as $fieldObject) {
				//FieldTypeMapping
				if ($fieldObject->getFieldType() == 'string') {
					array_push($fieldNameArray, $fieldObject->getName());
					array_push($valueArray, "\"".$_POST[$fieldObject->getName()]."\"");
				}
				else if ($fieldObject->getFieldType() == 'number') {
					array_push($fieldNameArray, $fieldObject->getName());
					array_push($valueArray, $_POST[$fieldObject->getName()]);
				}
				else if ($fieldObject->getFieldType() == 'reference') {
					array_push($fieldNameArray, $fieldObject->getName().'_id');
					array_push($valueArray, $_POST[$fieldObject->getName()]);
				}
			}
			$entity->editData($_POST['id'], $fieldNameArray, $valueArray);
			if ($entity->editData($_POST['id'], $fieldNameArray, $valueArray)) {
				header('Location: list?alert=editsuccess');
			}
			else {
				header('Location: list?alert=editfailed');
			}
		}
		
	}