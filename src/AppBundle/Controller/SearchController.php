<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
     * @Route("/search", name="search")
     */

class SearchController extends Controller
{
  /**
   * @Route("/", name="search")
   */
  public function search()
  {
  	$result = array();
    $query  = '';
    $artist = '';
    $song   = '';
    // If artist and song fields are in the url
  	if (@$_GET['artist'] && @$_GET['song'])
  	{
      // Replace spaces with underscores
      $artist         = str_replace(' ', '_', $_GET['artist']);
      $song           = str_replace(' ', '_', $_GET['song']);
      // Search query for Youtube
      $query          = $artist.' '.$song;
  		$key            = 'AIzaSyC_qjwgGkc60RIxBdKe4avOmHHyBQlGtug';
      // Init search API
  		$client 	    = new \Google_Client();
  		$client->setDeveloperKey($key);
  		$engine 	    = new \Google_Service_YouTube($client);
      // Response
  		$response     = $engine->search->listSearch('id, snippet', array('q' => $query, 'maxResults' => 40, 'type' => 'video',));
      foreach ($response['items'] as $get)
      {
        // For each found video, save data about video
        array_push($result,array(
          $get['snippet']['title'],
          $get['snippet']['channelTitle'],
          $get['id']['videoId'],
        ));
      }
    }
    else
    {
      // If inputed data wasn't correct
      $base_url = $this->getRequest()->getSchemeAndHttpHost();
      $this->addFlash('searchfail', 'Please, enter both fields');
      return $this->redirect($base_url);
    }
    // Render search page and pass variables
    return $this->render('search/search.html.twig', array('result'    => $result,
      'artist'    => $artist,
     	'song'      => $song,
    ));
  }
  /**
   * @Route("/{id}", name="show")
   */
  public function show($id)
  {
    // Replace spaces with underscores
    $artist     = str_replace(' ', '_',$_GET['artist']);
    $song       = str_replace(' ', '_',$_GET['song']);
    $key        = '5a590df6e3874ccbfea49eb47577d6a3';
    // Url of search results on their end
    $url        = 'http://api.musixmatch.com/ws/1.1/track.search?apikey='.$key.'&q_artist='.$artist.'&q_track='.$song.'&format=xml';
    // Parse response
    $get        = new \SimpleXMLElement(file_get_contents($url));
    // Find id of a specific song from the list
    $track_id   = $get->body->track_list->track->track_id;
    // Url of this song's info
    $url        = 'http://api.musixmatch.com/ws/1.1/track.lyrics.get?apikey='.$key.'&track_id='.$track_id.'&format=xml';
    $get        = new \SimpleXMLElement(file_get_contents($url));
    // Save lyrics to variable
    $lyrics     = $get->body->lyrics->lyrics_body;
    // If nothing is found
    if (strlen($lyrics) == 0) {
      $lyrics_exist = 'Hey, looks like we don\'t have lyrics for that one :(';
    }
    else
    {
      // If found
      $lyrics_exist = $lyrics;
    }
    // Render template for a video and pass data to it
    return $this->render('search/video.html.twig', array(
      'id'        => $id,
      'lyrics'    => $lyrics_exist,
      'artist'    => ucwords(str_replace('_', ' ', $artist)),
      'song'      => ucwords(str_replace('_', ' ', $song)),
    ));
  }
}
