<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_pagination.php                                       # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

class ibb_pagination
{
	var $psize   = 10;
	var $tpage   = 0;
	var $pers    = 0;
	var $page    = 0;
	var $offset  = 0;
	var $pernum  = '';
	var $limit   = '';
	var $varstr  = '';

	function ibb_pagination()
	{
        global $config;
        $this->pernum = $config['paginate_pernum'];
	}

	function fetch_paginate()
	{
		$i = 0;
		foreach($_GET as $k => $v)
		{
			$i++;
			$str = ($i == 1) ? '?' : '&';
			$this->varstr = ($k <> 'pg') ? $this->varstr.$str.$k.'='.$v : $this->varstr;
		}
		$this->varstr = str_replace("&amp;", "&", $this->varstr);
		$this->varstr = $this->varstr ? $this->varstr.'&' : '?';
		$this->page   = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
		$this->limit  = ($this->page -1) * $this->psize.','.$this->psize;
		$this->offset = ($this->page -1) * $this->psize;
	}

	function total_paginate($number)
	{
		$this->tpage = ceil($number / $this->psize);
		$this->pers  = ceil($this->tpage / $this->pernum);
	}

    function paginate($number = 0, $psize = 0)
    {
        global $theme, $lang;
        $this->psize = $psize ? $psize : $this->psize;
        $this->fetch_paginate();
        $this->total_paginate($number);
        $setpage = $this->page ? ceil($this->page / $this->pernum) : 1;
        $pagenum = ($this->tpage > $this->pernum) ? $this->pernum : $this->tpage;
        if($number <= $this->psize)
        {
        	$output = '';
        }
        else
        {
        	$theme->new_file("pagination", "pagination.tpl");
        	$theme->replace_tags("pagination",  array(
        		"PAGINATE_PAGES" => sprintf($lang['paginate_pages'], number_format($this->page), number_format($this->tpage)),
        	));
        	if ($this->page > 1)
        	{
        		$theme->insert_nest("pagination", "paginate_firstpage", array(
        			"FIRSTPAGE" => '<a href="'.$this->varstr.'pg=1" title="'.$lang['paginate_frstpage'].'">&laquo;</a>',
        		));
        		$theme->add_nest("pagination", "paginate_firstpage");
        	}
        	if ($this->page > 1)
        	{
        		$prev = $this->page-1;
        		$theme->insert_nest("pagination", "paginate_prevpage", array(
        			"PREVPAGE" => '<a href="'.$this->varstr.'pg='.$prev.'" title="'.$lang['paginate_prevpage'].'">&lsaquo;</a>',
        		));
        		$theme->add_nest("pagination", "paginate_prevpage");
        	}
        	$i = ($setpage-1)*$this->pernum;
        	for($j = $i; $j < ($i+$pagenum) && $j < $this->tpage; $j++)
        	{
        		$newpage = ($j+1);
        		if($this->page == $j+1)
        		{
                    $pagenumbers = '<span title="'.$lang['paginate_currpage'].'">'.($j+1).'</span>';
        		}
        		else
        		{
                    $pagenumbers = '<a href="'.$this->varstr.'pg='.$newpage.'" title="'.$lang['paginate_activepg'].'">'.($j+1).'</a>';
        		}
            	$theme->insert_nest("pagination", "paginate_pagenumbers", array(

            		"PAGENUMBERS" => $pagenumbers,
            	));
            	$theme->add_nest("pagination", "paginate_pagenumbers");
        	}
        	if($this->page < $this->tpage)
        	{
        		$next = $this->page + 1;
        		$theme->insert_nest("pagination", "paginate_nextpage", array(
        			"NEXTPAGE" => '<a href="'.$this->varstr.'pg='.$next.'" title="'.$lang['paginate_nextpage'].'">&rsaquo;</a>'
        		));
        		$theme->add_nest("pagination", "paginate_nextpage");
        	}
        	if($this->page < $this->tpage)
        	{
        		$theme->insert_nest("pagination", "paginate_lastpage", array(
        			"LASTPAGE" => '<a href="'.$this->varstr.'pg='.$this->tpage.'" title="'.$lang['paginate_lastpage'].'">&raquo;</a>'
        		));
        		$theme->add_nest("pagination", "paginate_lastpage");
        	}
        	$output = $theme->output("pagination", TRUE);
        }
        return $output;
    }
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
