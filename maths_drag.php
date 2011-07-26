<HTML>
<HEAD>
    <script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
</HEAD>
<BODY>
    <DIV id="move" style="width:150px;height:150px;background-color:pink;border:1px solid #999999"> &nbsp; </DIV>
	
	<DIV id="correct_zone" style="width:150px;height:150px;background-color:green;border:1px solid #999999"> &nbsp; </DIV>
    <SCRIPT>
    $(document).ready(function(){
        $("#move").draggable();
		$("correct_zone").droppable();
		
    });
    </SCRIPT>
 
</BODY>
</HTML>