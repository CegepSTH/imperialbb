<style type="text/css">
div#calendar{
  margin:0px auto;
  padding:0px;
  width: 602px;
  font-family:Helvetica, "Times New Roman", Times, serif;
}
 
div#calendar div.box{
    position:relative;
    top:0px;
    left:0px;
    width:100%;
    height:40px;
    background-color:   #787878 ;      
}
 
div#calendar div.header{
    line-height:40px;  
    vertical-align:middle;
    position:absolute;
    left:11px;
    top:0px;
    width:582px;
    height:40px;   
    text-align:center;
}
 
div#calendar div.header a.prev,div#calendar div.header a.next{ 
    position:absolute;
    top:0px;   
    height: 17px;
    display:block;
    cursor:pointer;
    text-decoration:none;
    color:#FFF;
}
 
div#calendar div.header span.title{
    color:#FFF;
    font-size:18px;
}
 
 
div#calendar div.header a.prev{
    left:0px;
}
 
div#calendar div.header a.next{
    right:0px;
}
 
 
 
 
/*******************************Calendar Content Cells*********************************/
div#calendar div.box-content{
    border:1px solid #787878 ;
    border-top:none;
}
 
 
 
div#calendar ul.label{
    float:left;
    margin: 0px;
    padding: 0px;
    margin-top:5px;
    margin-left: 5px;
}
 
div#calendar ul.label li{
    margin:0px;
    padding:0px;
    margin-right:5px;  
    float:left;
    list-style-type:none;
    width:80px;
    height:40px;
    line-height:40px;
    vertical-align:middle;
    text-align:center;
    color:#000;
    font-size: 15px;
    background-color: transparent;
}
 
 
div#calendar ul.dates{
    float:left;
    margin: 0px;
    padding: 0px;
    margin-left: 5px;
    margin-bottom: 5px;
}
 
/** overall width = width+padding-right**/
div#calendar ul.dates li{
    margin:0px;
    padding:0px;
    margin-right:5px;
    margin-top: 5px;
    line-height:80px;
    vertical-align:middle;
    float:left;
    list-style-type:none;
    width:80px;
    height:80px;
    font-size:25px;
    background-color: #DDD;
    color:#000;
    text-align:center; 
}
 
:focus{
    outline:none;
}
 
div.clear{
    clear:both;
}     
</style>

<div id="calendar">
	<div class="box">
		<div class="header">
			<a class="prev" href="calendar.php?month={PREV_MONTH}&year={PREV_YEAR}">{L.Prev}</a>
			<span class="title">{CURRENT}</span>
			<a class="next" href="calendar.php?month={NEXT_MONTH}&year={NEXT_YEAR}">{L.Next}</a>
		</div>
		<div class="box-content">
            <ul class="label">
            </ul>
        </div>
        <div class="clear"></div>
        <ul class="label">
        <!-- BLOCK labels -->
			<li class="{LABEL_CLASS} title">{LABEL_NAME}</li>
		<!-- END BLOCK labels -->
		</ul>
        <ul class="dates">
        <!-- BLOCK date_day -->
			<li id="li-{CURRENT_DATE}" class="{DATE_CLASS}">
				<p style="word-wrap: break-word; line-height:1em; margin:0;	">
					{DATE_CONTENT}
				</p>
				{block_event_item}
			</li>
        <!-- END BLOCK date_day -->
        </ul>
	</div>
	<div class="clear"></div>
</div>
<!-- BLOCK event_item -->
<p style="margin:0px;word-wrap: break-word; line-height: 1em; font-size: 0.45em;text-align:left;padding-left:5px; font-weight: normal;">
	<a href="{E_LINK}">{E_TITLE}</a>
</p>
<!-- END BLOCK event_item -->
  



