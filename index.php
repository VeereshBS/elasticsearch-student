<?php

include 'api.php';

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>student ElasticSearch Example</title>
	<style type="text/css">

		/* Layout */
		body {
			min-width: 630px;
		}

		#container {
			padding-left: 200px;
			padding-right: 190px;
		}
		
		#container .column {
			position: relative;
			float: left;
		}
		
		#center {
			padding: 10px 20px;
			width: 100%;
		}
		
		#left {
			width: 200px;
			padding: 0 10px;
			right: 240px;
			margin-left: -100%;
		}
		
		#right {
			width: 130px;
			padding: 0 10px;
			margin-right: -100%;
		}
		
		#footer {
			clear: both;
		}
		
		/* IE hack */
		* html #left {
			left: 150px;
		}

		/* Make the columns the same height as each other */
		#container {
			overflow: hidden;
		}

		#container .column {
			padding-bottom: 1001em;
			margin-bottom: -1000em;
		}

		/* Fix for the footer */
		* html body {
			overflow: hidden;
		}
		
		* html #footer-wrapper {
			float: left;
			position: relative;
			width: 100%;
			padding-bottom: 10010px;
			margin-bottom: -10000px;
			background: #fff;
		}

		/* Aesthetics */
		body {
			margin: 0;
			padding: 0;
			font-family:Sans-serif;
			line-height: 1.5em;
		}
		nav ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
		}
		
		nav ul a {
			color: darkgreen;
			text-decoration: none;
		}

		#header, #footer {
			font-size: large;
			padding: 0.3em;
			background: #3b4151;
                        color: #fff;
                        text-align: center;
                        
		}

		#left {
			background: #F7FDEB;
                        font-size: 12px;
		}
		
		#right {
			background: #F7FDEB;
                         font-size: 12px;
		}

		#center {
			background: #fff;
                         font-size: 12px;
		}

		#container .column {
			padding-top: 1em;
		}
                a {
                    color: #fff;
                }
                .student {
                    width: 180px;
                    height: 100px;
                    float: left;
                    border: #3b4151 solid 1px;
                    padding: 5px;
                    margin: 5px;
                }
		
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>

<body>

	<header id="header"><p> ElasticSearch Filters Example [ Facets || Aggregation || Search ] </p></header>

	<div id="container">

		<main id="center" class="column">
			<article>
			
				<h1>Students [ Total: <?php     if(isset($result['hits']['total'])) echo $result['hits']['total']; else echo '0'; ?> ]</h1>
				<?php
                                
                                 if(isset($result['hits']['hits'])&& is_array($result['hits']['hits'])&& count($result['hits']['hits']) > 0){
                                     
                                     foreach($result['hits']['hits'] as $student){
                                     
                                     ?>
                                
                                            <div class="student"> 
                                                <strong> Name: </strong> <?php echo $student['_source']['Name']; ?> <br>
                                                <strong> Department: </strong> <?php echo $student['_source']['Department']; ?> <br>
                                                <strong> Marks: </strong> <?php echo $student['_source']['Marks']; ?> <br>
                                                <strong> Year: <span style='color: green; font-size: 20px; '><?php echo str_repeat(" * ",$student['_source']['Semester']); ?> </span> </strong><br>
                                            </div>
                                     
                                     <?php
                                     }
                                 }
                                
                                ?>
			
			</article>								
		</main>

		<nav id="left" class="column">
                    
                         <form  id="filterForm" action="" method="POST">
                             <input type="text" name="search" placeholder="Search" value="<?php if(isset($_POST['search'])) echo $_POST['search']; ?>" /> <br>
                        <?php
                            if(isset($result['aggregations'])&& is_array($result['aggregations'])&& count($result['aggregations']) > 0){
                                //print_r($result['aggregations']);exit;
                                foreach($result['aggregations'] as $index => $value)    {
                        ?>        
                            <br> <strong> <?php echo $index; ?></strong> <br>
                            
                           <?php
                               if(isset($result['aggregations'][$index][$index]['buckets'])&& is_array($result['aggregations'][$index][$index]['buckets'])&& count($result['aggregations'][$index][$index]['buckets']) > 0){
                                    foreach ($result['aggregations'][$index][$index]['buckets'] as $buckets){
                                   
                            ?>
                                <input type="checkbox" class="leftFilters" name="filters[<?php echo $index; ?>][]" value="<?php echo $buckets['key']; ?>" <?php if(isset($postFilters[$index]) && in_array($buckets['key'],$postFilters[$index])) echo " checked "; ?>  /> <?php echo $buckets['key']; ?>  [ <?php echo $buckets['doc_count']; ?> ]   <br>   
                                
                           <?php   
                                    }
                               }
                            ?>   
                           
                         <?php     
                                }
                            }
                        ?>
			
			
                               
			
                        </form>
                       
                        
                       
			

		</nav>

		<div id="right" class="column">
			
		</div>

	</div>

	<div id="footer-wrapper">
            <footer id="footer"><p> Veeresh Sasalawad </p></footer>
	</div>
        <script>
            
            $(document).ready(function(){
                
                $(".leftFilters").change(function() {
                    if(this.checked || !this.checked ) {
                        $('#filterForm').submit();
                    }
                });
            });
            
        </script>    
        
        
</body>

</html>