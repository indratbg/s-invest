<?php
class XmlDirectoryCommand extends CConsoleCommand
{	
    public function run($args)
    {	
		//AttendanceGenerator::SixtyDaysScheduler();
		DirMaintain::maintainXmlDir();
    }//end function run
}
