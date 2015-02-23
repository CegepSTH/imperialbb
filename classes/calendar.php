<?php
if(!isset($root_path)) {
	$root_path = "";
}

require_once("template.php");

/**
* @author  Xu Ding
* @modification mrtryhard 
* @email   thedilab@gmail.com
* @website http://www.StarTutorial.com
* http://www.startutorial.com/articles/view/how-to-build-a-web-calendar-in-php
**/
class Calendar {  
    private $dayLabels;
    private $monthLabels;
    private $currentYear = 0; 
    private $currentMonth = 0;
    private $currentDay = 0;  
    private $currentDate = null;  
    private $daysInMonth = 0;
    private $naviHref = null;
    // Array of array as: [] =>( "title" => , "link" => , "date" => array ("day" => , "month" => , "year" =>) )
    private $events = array();
     
    /**
     * Constructor
     */
    public function __construct(array &$lang){     
        $this->naviHref = htmlspecialchars($_SERVER['PHP_SELF']);
        $this->dayLabels = array($lang["Sunday"], 
			$lang["Monday"],
			$lang["Tuesday"], 
			$lang["Wednesday"], 
			$lang["Thursday"], 
			$lang["Friday"], 
			$lang["Saturday"]);
		$this->monthLabels = array($lang["January"], $lang["February"], 
			$lang["March"], $lang["April"], $lang["May"], $lang["June"], 
			$lang["July"], $lang["August"], $lang["September"], 
			$lang["October"], $lang["November"], $lang["December"]);
    }
        
    /**
     * Adds an event with a title and a link.
     * 
     * @param $title Title of the link / event. Must be set.
     * 
     * @param $link Link to the event, if any. Can be empty.
     * 
     * @param $date array ("day" => , "month" => , "year" =>) 
     * If there's only a day set, each month of each year it will show. 
     * If there's a day and month set, this occurence repeats each year.
     * If all set, only once.
     * 
     */
    public function addEvent($title, $link, array $date) {
		if(!is_string($title) || empty($title) 
			|| !is_string($link) || empty($date)) 
		{
			return;
		}
		
		$this->events[] = array("title" => trim($title), 
			"link" => trim($link),
			"date" => $date);
	}
    
    /**
    * print out the calendar
    */
    public function getTPL() {		
        $year = null;
        $month = null;
         
        if(null == $year && isset($_GET['year'])) {
            $year = $_GET['year'];
        } else if(null == $year) {
            $year = date("Y",time());  
        }          
         
        if($month == null && isset($_GET['month'])) {
            $month = $_GET['month'];
        } else if(null == $month) {
            $month = date("m",time());
        }                  
         
        $this->currentYear = $year;         
        $this->currentMonth = $month;
        $this->daysInMonth = $this->_daysInMonth($month,$year);  
        
		$nextMonth = $this->currentMonth == 12 ? 1:intval($this->currentMonth) + 1;
        $nextYear = $this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;
        $preMonth = $this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;
        $preYear = $this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;
        
		$tpl = new Template("calendar.tpl");
        $tpl->setVars(array("PREV_MONTH" => sprintf('%02d',$preMonth), 
			"PREV_YEAR" => $preYear,
			"CURRENT" => date('Y', strtotime($this->currentYear))." - ".$this->map(intval($this->currentMonth) - 1),
			"NEXT_MONTH" => sprintf("%02d", $nextMonth), 
			"NEXT_YEAR" => $nextYear
			));  
         
        foreach($this->dayLabels as $index => $label) {
			$tpl->addToBlock("labels", array("LABEL_CLASS" => ($label == 6?'end title':'start title'), 
				"LABEL_NAME" => $label));
        }
  
		$weeksInMonth = $this->_weeksInMonth($month,$year);
		// Create weeks in a month
		for($i = 0; $i < $weeksInMonth; $i++ ){
            //Create days in a week
            for($j = 0; $j < 7; $j++) {
                $this->_showDay($tpl, $i*7+$j);
            }
        }

        return $tpl;   
    }
    
    /**
     * Translate the month.
     */ 
    private function map($month) {
		return $this->monthLabels[$month];
	}
	
    /**
    * create the li element for ul
    */
    private function _showDay(Template &$tpl, $cellNumber) {
        if($this->currentDay == 0) {
            $firstDayOfTheWeek = date('N', strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                $this->currentDay = 1;
            }
        }
         
        if(($this->currentDay != 0) && ($this->currentDay <= $this->daysInMonth)) {
            $this->currentDate = date('Y-m-d', strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
            $cellContent = $this->currentDay;
            $this->currentDay++;   
        } else {
            $this->currentDate = null;
            $cellContent = null;
        }
        
        // Check events.
        $eventBlock = "";

        foreach($this->events as $id => $array) {
			if($this->events[$id]["date"]["day"] == $this->currentDay) {
				if(empty($this->events[$id]["date"]["month"]) 
					|| intval($this->events[$id]["date"]["month"]) == intval($this->currentMonth)) 
				{
					if (empty($this->events[$id]["date"]["year"]) 
						|| intval($this->events[$id]["date"]["year"]) == intval($this->currentYear))
					{
						$eventBlock .= $tpl->renderBlock("event_item", 
							array("E_TITLE" => $this->events[$id]["title"], 
								"E_LINK" => $this->events[$id]["link"]));
					}
				}
			}
		}        
        
		$tpl->addToBlock("date_day", array("DATE_CLASS" => ($cellNumber % 7 == 1 ? ' start ': ($cellNumber%7==0?' end ' : ' ')).
			($cellContent == null ? 'mask' : ''), 
			"CURRENT_DATE" => $this->currentDate, 
			"DATE_CONTENT" => $cellContent,
			"block_event_item" => $eventBlock));
    }
     
    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month = null, $year = null){
        if(null == ($year)) {
            $year = date("Y",time()); 
        }
         
        if(null == ($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);
        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths/7);
        $monthEndingDay= date('N', strtotime($year.'-'.$month.'-'.$daysInMonths));
        $monthStartDay = date('N', strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay < $monthStartDay){
            $numOfweeks++;
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month = null, $year = null){
        if(null == ($year)) {
            $year = date("Y", time()); 
		}
 
        if(null == ($month)) {
            $month = date("m",time());
		}
             
        return date('t', strtotime($year.'-'.$month.'-01'));
    }
     
}
