<?php

/**
 * twitter-timeline-php : Twitter API 1.1 user timeline implemented with PHP, a little JavaScript, and web intents
 * 
 * @package		twitter-timeline-php
 * @author		Kim Maida <contact@kim-maida.com>
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link		http://github.com/kmaida/twitter-timeline-php
 * @credits		Thank you to <http://viralpatel.net/blogs/twitter-like-n-min-sec-ago-timestamp-in-php-mysql/> for base for "time ago" calculations 
 *
**/

   if ( !( $database = mysql_connect( "localhost","root", "apmsetup" ) ) )                      
            die( "Could not connect to database </body></html>" );
			
			
	mysql_query("set names utf8");
   
    // open Products database
    if ( !mysql_select_db( "opis", $database ) )
       die( "Could not open products database </body></html>" );
			 
	$query_food = "select * from twitter_words";
	$query_verb = "select * from twitter_eaten_words";
	
    $query_food_result = mysql_query( $query_food, $database ); 
	$query_verb_result = mysql_query( $query_verb, $database ); 
	
	$food_Number = 0;
	$verb_Number = 0;
	
	while($row = mysql_fetch_row($query_food_result)){
		$food_array[$food_Number][0] = $row[0];
		$food_array[$food_Number][1] = $row[1];
		$food_Number++;
	}
	
	while($row = mysql_fetch_row($query_verb_result)){
		$verb_array[$verb_Number] = $row[0];
		$verb_Number++;
	}

############################################################### 
	## SETTINGS
	
	// Set access tokens <https://dev.twitter.com/apps/>
	$settings = array(
		'consumer_key' => "mCwwXlafubyU57W2CnE7yEJVc",
		'consumer_secret' => "TDDW2DyCaWF9pO7qQyG8mnoWJ5AYAFqU2G7PRNiYGLEVnfsS6D",
		'oauth_access_token' => "240277552-iBjL8t6pqyUwVfyWcGfDiM4yKdAhHyjzHWhEu6qK",
		'oauth_access_token_secret' => "C2FHAn2ayo3rNuyC0BEXTfqoVVUBixZG73VN7h5UHap1d"
	);
	
	// Set API request URL and timeline variables if needed <https://dev.twitter.com/docs/api/1.1>
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	//$twitterUsername = $ozozotoru;
	$twitterUsername = $twt_id;
	$tweetCount = 1000; //최신 1000개
	
	// Use private tokens for development if they exist; delete when no longer necessary
	$tokens = '_utils/tokens.php';
	is_file($tokens) AND include $tokens;
	
	// Require the OAuth class
	require_once('twitter-api-oauth.php');
	
	$matching = 0;
	$page = 1;
	
	$score = 0;
	$dawnScore = 0;
	
############################################################### 	
	## DO SOMETHING WITH THE DATA
	
//-------------------------------------------------------------- Format the time(ago) and date of each tweet
	
	function timeAgo($dateStr) {
		$timestamp = strtotime($dateStr);	 
		$day = 60 * 60 * 24;
		$today = time(); // current unix time
		$since = $today - $timestamp;
		 
		 # If it's been less than 1 day since the tweet was posted, figure out how long ago in seconds/minutes/hours
		 if (($since / $day) < 1) {
		 
		 	$timeUnits = array(
				   array(60 * 60, 'h'),
				   array(60, 'm'),
				   array(1, 's')
			  );
			  
			  for ($i = 0, $n = count($timeUnits); $i < $n; $i++) { 
				   $seconds = $timeUnits[$i][0];
				   $unit = $timeUnits[$i][1];
			 
				   if (($count = floor($since / $seconds)) != 0) {
					   break;
				   }
			  }
		 
			  return "$count{$unit}";
			  
		# If it's been a day or more, return the date: day (without leading 0) and 3-letter month
		 } else {
			  return date('j M', strtotime($dateStr));
		 }	 
	}
	
//-------------------------------------------------------------- Format the tweet text (links, hashtags, mentions)
	
	function formatTweet($tweet) {
		$linkified = '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@';
		$hashified = '/(^|[\n\s])#([^\s"\t\n\r<:]*)/is';
		$mentionified = '/(^|[\n\s])@([^\s"\t\n\r<:]*)/is';
		
		$prettyTweet = preg_replace(
			array(
				$linkified,
				$hashified,
				$mentionified
			), 
			array(
				'<a href="$1" class="link-tweet" target="_blank">$1</a>',
				'$1<a class="link-hashtag" href="https://twitter.com/search?q=%23$2&src=hash" target="_blank">#$2</a>',
				'$1<a class="link-mention" href="http://twitter.com/$2" target="_blank">@$2</a>'
			), 
			$tweet
		);
		
		return $prettyTweet;
	}
	
	function scoringWords($tweet, $food_num, $verb_num, $food_array, $verb_array){
//		echo 'hehehe';
		$positive_flag = 0;
		$negative_flag = 0;

		for($for_p=0; $for_p<$food_num; $for_p++) //food array
		{
			// 한 트윗에 긍정, 부정 단어 둘 다 있으면?
			//-> 없다 치자ㅠ
			
			//치킨머금 = 치킨 머금 으로 치려면..음 정규식 치기 귀차나..
			
			for($for_j=0; $for_j < $verb_num ; $for_j++ ) //동사~~
			
//			echo 'hoo';
//			echo $food_array[$for_p][0].$verb_array[$for_j];
			
			if(  (strpos($tweet, $food_array[$for_p][0].$verb_array[$for_j]) !== false)  
	 		  ||  strpos($tweet, $food_array[$for_p][0].' '.$verb_array[$for_j]) !== false )
				//단어가 매치가 되면. '치킨머금'    '치킨 머금'
			{  //tweet -> DB에서 받아온거.
				if($food_array[$for_p][1] == 0) //
				{
					echo ("negative word");
					$negative_flag = -1;
				}
				else   // word array가 1이면 positive
				{
					echo("positive word");
					$positive_flag = 1;
				}
			}
		}
		
		//print($positive_flag + $negative_flag);
		return $positive_flag + $negative_flag;
		// 단어 결과 검색 결과  //
	}
	
// -------------------------------------------------------------- Timeline HTML output
	# This output markup adheres to the Twitter developer display requirements (https://dev.twitter.com/terms/display-requirements)
	
	###############################################################
	## MAKE GET REQUEST
	
	for($page=1; $page<6; $page++)
	{

	$getfield = '?screen_name=' . $twitterUsername . '&page=' .$page. '&count=' . $tweetCount;
	$twitter = new TwitterAPITimeline($settings);
	
	$json = $twitter->setGetfield($getfield)	// Note: Set the GET field BEFORE calling buildOauth()
				  	->buildOauth($url, $requestMethod)
				 	->performRequest();
				 			
	$twitter_data = json_decode($json, true);	// Create an array with the fetched JSON data
	
	# Open the timeline list
	echo '<ul id="tweet-list" class="tweet-list">';
	
	# The tweets loop
	foreach ($twitter_data as $tweet) {
	
		$retweet = $tweet['retweeted_status'];
		$isRetweet = !empty($retweet);
		
		# Retweet - get the retweeter's name and screen name
		$retweetingUser = $isRetweet ? $tweet['user']['name'] : null;
		$retweetingUserScreenName = $isRetweet ? $tweet['user']['screen_name'] : null;
		
		# Tweet source user (could be a retweeted user and not the owner of the timeline)
		$user = !$isRetweet ? $tweet['user'] : $retweet['user'];	
		$userName = $user['name'];
		$userScreenName = $user['screen_name'];
		$userAvatarURL = stripcslashes($user['profile_image_url']);
		$userAccountURL = 'http://twitter.com/' . $userScreenName;
		
		# The tweet
		$id = $tweet['id'];
		$formattedTweet = !$isRetweet ? $tweet['text'] : $retweet['text'];
		//$formattedTweet = !$isRetweet ? formatTweet($tweet['text']) : formatTweet($retweet['text']);
		$statusURL = 'http://twitter.com/' . $userScreenName . '/status/' . $id;
		
		
		$tweeted_hour = substr($tweet['created_at'], 11, 2);
		$tweeted_hour = intval($tweeted_hour) + 9;
		
		print($tweeted_hour);
		
		$date = timeAgo($tweet['created_at']);
		
		# Reply
		$replyID = $tweet['in_reply_to_status_id'];
		$isReply = !empty($replyID);

		# Tweet actions (uses web intents)
		$replyURL = 'https://twitter.com/intent/tweet?in_reply_to=' . $id;
		$retweetURL = 'https://twitter.com/intent/retweet?tweet_id=' . $id;
		$favoriteURL = 'https://twitter.com/intent/favorite?tweet_id=' . $id;	
		
		$score += scoringWords($formattedTweet, $food_Number, $verb_Number, $food_array, $verb_array);
		
		//트위터 내용, 
		if($tweeted_hour > 23 && $tweeted_hour < 30)
		{
			$dawnScore -= 0.1;
		}
?>
	

<?php 
	}	# End tweets loop
}
	
	# Close the timeline list
	echo '</ul>';
	
	echo '<br>';
	print("키워드 점수 : ".$score);
	echo '<br>';
	print("새벽 트윗 벌점 : ".$dawnScore);
	
	$discount_fee_2 = ($score+$dawnScore)*50;
	if($discount_fee_2 < 0)
		$discount_fee_2 = 0;
	
	$discount_fee += discount_fee_2;
	
	# echo $json; // Uncomment this line to view the entire JSON array. Helpful: http://www.freeformatter.com/json-formatter.html
?>