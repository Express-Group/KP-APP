<div class="top_news common_p">
						  <h4 class="topic"><?php echo $content['title']; ?></h4>
                           <?php
						   		foreach($content['news_content'] as $key => $news_data)
								{
								?>
                                	<p> <i class="fa fa-angle-right"></i> <a href="#"><?php echo $news_data; ?></a></p>
                                    
                                <?php	
								}
						   ?>
						  <span class="arrow_right"><a href="#" class="landing-arrow">arrow</a></span> 
						</div>
						
