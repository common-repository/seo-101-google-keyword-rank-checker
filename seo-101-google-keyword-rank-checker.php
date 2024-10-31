<?php
/**
 Plugin Name: SEO 101 Google Keyword Rank Checker
 Plugin URI: http://www.seo101.net
 Description: Check the rankings of your keyword in Google
 Version: 1.1
 Author: Seo101
 Author URI: http://www.seo101.net
 */

function rankchecker_page() {
?>
	<style type="text/css">
    body{font-family:Verdana, Geneva, sans-serif; color:#666;}
    #center{width:350px;margin:5px auto 0 auto;}
    form { float:left; width:380px; }
    input, label { width:70%; clear:left; float:left; margin-top:5px; }
	input{color:#999;}
    input[type="submit"] { width:30%; clear:left; float:left; margin-top:20px; color:#666; }

    .small{ font-size:0.7em; color:#999999}
	h3{font-size:1.4em;}
    #kwdz{ width:400px; position:relative; top:5px;}
	#domainz{width:400px; margin-top:10px;}
	#result{margin-top:20px;}
    </style>
<div id="center">

<p>
<strong><font size=4 color="#DF0101">Note: </font><strong>Some servers do not allow to contact Google and some IP addresses are blocked by Google because of too many requests. In this case the plugin will <b>not</b> work.
</p>
<p>
Check my blog: <a href="http://www.seo101.net">seo101.net</a>
</p>


<form action="options-general.php?page=seo-101-google-keyword-rank-checker" method="post">
    <div id="domainz">
        <label>Which Google to use (.co.uk, .com, .nl, .br, etc...):</label>
        <input name="googleurl" value="www.google.com" />
    </div>
    <div id="domainz">
        <label>Domain:</label>
        <input name="domain" value="seo101.net" onclick="this.value=''" />
    </div>
    <div id="kwdz">
        <label>Keywords:</label>
        <input name="keywords" value="true success with fiver" onclick="this.value=''" />
    </div>
    <input type="submit" name="check" value="Get position" />
</form>



<br style="clear:both" />
<?php

$i = 1; $hit = 0;

if($_POST) {

	// Clean the post data and make usable
	$domain = filter_var($_POST['domain'], FILTER_SANITIZE_STRING);
	$googleurl= filter_var($_POST['googleurl'], FILTER_SANITIZE_STRING);
	$keywords = filter_var($_POST['keywords'], FILTER_SANITIZE_STRING);
		// Remove begining http and trailing /
		$domain = substr($domain, 0, 7) == 'http://' ? substr($domain, 7) : $domain;
		$domain = substr($domain, -1) == '/' ? substr_replace($domain, '', -1) : $domain;
		$googleurl= substr($googleurl, 0, 7) == 'http://' ? substr($googleurl, 7) : $googleurl;
		$googleurl= substr($googleurl, -1) == '/' ? substr_replace($googleurl, '', -1) : $googleurl;
		// Replace spaces with +
		$keywords = strstr($keywords, ' ') ? str_replace(' ', '+', $keywords) : $keywords;
	// Grab the Google page using the chosen keywords
	$html = new DOMDocument();
	@$html->loadHtmlFile('http://' . $googleurl . '/search?q='.$keywords.'&num=100');
	$xpath = new DOMXPath($html);
	// Store the domains to nodes
	$nodes = $xpath->query('//div[1]/cite');

	// Loop through the nodes to look for our domain
	$hit = 2;
	foreach ($nodes as $n){
		// echo '<div style="font-size:0.7em">'.$n->nodeValue.'<br /></div>'; // Show all links
		if (strstr($n->nodeValue, $domain)) {
			$message = 'Position '.$i.'<br />'; $hit = 1;
			break;
		}
		else { ++$i; }
	}
}
?>

    <div id="result">
        <?php // Echo the result
        if ($hit == 1) { echo '<h2>'.$message.'</h2>'; }
        else if ($hit >= 2) { echo '<h2>Not found!</h2>'; }
        ?>
    </div>

</div>


<?php
}


function rankchecker_menu() {
	if (is_admin()) {
		add_options_page('Seo101 Rank Checker', 'Seo101 Rank Checker', 'administrator','seo-101-google-keyword-rank-checker', 'rankchecker_page');
	}
}

// Admin menu items
add_action('admin_menu', 'rankchecker_menu');

?>