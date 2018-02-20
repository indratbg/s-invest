<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ShareController extends Controller
{
	public function accessRules()
	{
		return array(
			array('allow', 'users' => array('@')),
			array('deny', 'users' => array('*')),
		);
	}
}