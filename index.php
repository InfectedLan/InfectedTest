<?php
	require_once 'testApi/InfectedTestbed.php';
	require_once 'testApi/WebReporter.php';
	
	$reporter = new WebReporter();

	//Run the test	
	$testbed = new InfectedTestbed($reporter);
	$testbed->runTests($reporter);

	//Done testing. This is for reporters that report after testing is done
	$reporter->doneTesting();
?>