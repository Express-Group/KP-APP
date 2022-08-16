<?php
$url_segment1 = $this->uri->segment(1); 
$url_segment2 = $this->uri->segment(2); 
?>
<div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="AskPrabhu">
      <table>
      <tr>
      <td class="AskPrabhuLeft">
      <h2 class="topic WhiteTopic"><?php echo ($url_segment1=="prabhu-chawla" && $url_segment2=="column")? "Power and Politics": "Ask Prabhu";?></h2>
      <h4 class="Italic">Prabhu Chawla is the Editorial Director of The New Indian Express group.</h4>
      <p>Having begun as a reporter and spent over 40 years in journalism during which time he has headed The India Today group and The Indian Express as Editor, Chawla has witnessed and recorded dramatic changes in Indian democracy &#8212 from the trauma of the Emergency, the pathos of Rajiv Gandhi's rise and fall and the angst of Mandal to the churning of liberalisation and the rise of coalition politics.</p>
      </td>
      <td class="AskPrabhuRight">
      <figure>
      <img src="<?php echo image_url; ?>images/FrontEnd/images/prabhu.png" title="Ask Prabhu">
      </figure>
      </td>
      </tr>
      </table>
      </div>
</div>
</div>