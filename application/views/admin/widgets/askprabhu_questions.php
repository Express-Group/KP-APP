<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="AskPrabhuReply">
      <h2 class="AskPrabhuTitle title">Your Replies</h2>
      <div id="content" ></div>
      <table>
        <?php 
$Order='';
$archive = '';
$per_page = 5;
$Qsn_limit = ($this->input->get('per_page') != '') ? $this->input->get('per_page'):0;
$get_result = $this->widget_model->display_askprabhu_qnlist($Qsn_limit,$per_page); 
$config['total_rows'] = $this->db->query('CALL display_askprabhu_qnlist("'.$Order.'")')->num_rows();
$config['per_page'] = $per_page; 
$config['custom_num_links'] = 5;

$config['page_query_string'] = TRUE;
$config['enable_query_strings']=TRUE;
$config['cur_tag_open'] = "<a href='javascript:void(0);' class='active'>";
$config['cur_tag_close'] = "</a>";
$this->pagination->initialize($config);
//$PaginationLink = $this->pagination->create_links();
$PaginationLink = $this->pagination->custom_create_links();
		
		foreach($get_result as $result)
		{
			$questions=$result['Questiontext'];
			$answers=$result['AnswerInHTML'];
			//$answers = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$answers); 
			$username=$result['UserName'];
			$place=$result['Place'];
			$date=$result['Modifiedon'];
			$display_date=date("d-m-Y",strtotime($date));
			
?>
        <tr>
          <td class="UserDetail"><p><?php echo $username ?></p>
            <p><?php echo $place ?> <span class="WidthFloat_L"><?php echo $display_date ?></span></p></td>
          <td class="UserQuestion"><p>Q: <?php echo strip_tags($questions);  ?> </p>
            <div class="down_arrow"> </div></td>
        </tr>
        <tr>
          <td colspan="2" class="PrabhuAnswer"><div>A: <?php echo strip_tags($answers);  ?></div></td>
        </tr>
        <?php  }?>
      </table>
      <div id="loading" ></div>
      <div class="pagina"> <?php echo $PaginationLink ;?> <?php echo $archive;?></div>
    </div>
  </div>
</div>
<?php ?>
