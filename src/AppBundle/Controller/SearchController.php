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
    	if (@$_GET['artist'] && @$_GET['song']) 
    	{
            $artist         = str_replace(' ', '_', $_GET['artist']);
            $song           = str_replace(' ', '_', $_GET['song']);
            $query          = $artist.' '.$song;
			$key            = 'AIzaSyC_qjwgGkc60RIxBdKe4avOmHHyBQlGtug';
			$client 	    = new \Google_Client();
			$client->setDeveloperKey($key);
			$engine 	    = new \Google_Service_YouTube($client);
			$response       = $engine->search->listSearch('id, snippet', array('q' => $query, 'maxResults' => 40, 'type' => 'video',));
            foreach ($response['items'] as $get)
            {
                array_push($result,array(
                    $get['snippet']['title'],
                    $get['snippet']['channelTitle'],
                    $get['id']['videoId'],
                ));
            }
        }
        else
        {
            $base_url = $this->getRequest()->getSchemeAndHttpHost();
            $this->addFlash('searchfail', 'Please, enter both fields');
            return $this->redirect($base_url);
        }
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
        $artist     = str_replace(' ', '_',$_GET['artist']);
        $song       = str_replace(' ', '_',$_GET['song']);
        $key        = '5a590df6e3874ccbfea49eb47577d6a3';
        $url        = 'http://api.musixmatch.com/ws/1.1/track.search?apikey='.$key.'&q_artist='.$artist.'&q_track='.$song.'&format=xml';
        $get        = new \SimpleXMLElement(file_get_contents($url));
        $track_id   = $get->body->track_list->track->track_id;
        $url        = 'http://api.musixmatch.com/ws/1.1/track.lyrics.get?apikey='.$key.'&track_id='.$track_id.'&format=xml';
        $get        = new \SimpleXMLElement(file_get_contents($url));
        $lyrics     = $get->body->lyrics->lyrics_body;
        if (strlen($lyrics) == 0) {
            $lyrics_exist = 'Hey, looks like we don\'t have lyrics for that one :(';
        }
        else 
        {
            $lyrics_exist = $lyrics;
        }
        return $this->render('search/video.html.twig', array(
            'id'        => $id,
            'lyrics'    => $lyrics_exist,
            'artist'    => ucwords(str_replace('_', ' ', $artist)),
            'song'      => ucwords(str_replace('_', ' ', $song)),
            ));
    }
}