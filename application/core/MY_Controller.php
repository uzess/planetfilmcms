<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('groups_m');
    $this->load->model('setting_m');
    $this->data['setting'] = $this->setting_m->get('*', array('id' => 1), 1);
  }

  public function getYouTubeImage($url, $size)
  {
    if ($url == "")
    {
      return false;
    }
    $size = ($size == "") ? "big" : $size;

    $results = explode("/watch?v=", $url);
    if (count($results) < 2)
      return false;
    $vid = ( $results == "" ) ? $url : $results[1];
    if ($size == "small")
    {
      return "http://img.youtube.com/vi/" . $vid . "/2.jpg";
    }
    else
    {
      return "http://img.youtube.com/vi/" . $vid . "/0.jpg";
    }
  }

  public function formatYoutubeLink($link)
  {
    $strPOs = strpos($link, "src=");
    $newString = substr($link, $strPOs);
    $strExplode = explode(" ", $newString);
    $onlySrc = $strExplode[0];
    $exploded = explode('"', $onlySrc);
    $link = stripslashes($exploded[0]);

    if (strpos($link, "/embed/") > 0)
      $url = str_replace("/embed/", "/v/", $link);
    elseif (strpos($link, "/watch?v=") > 0)
      $url = str_replace("/watch?v=", "/v/", $link);
    elseif (strpos($link, "youtu.be/") > 0)
      $url = str_replace("youtu.be", "youtube.com/v/", $link);
    elseif (strpos($link, "/v/") > 0)
      $url = $link;

    return $url;
  }

  public function skip_char($urlname)
  {
    $skip_char = array('&', '|',':', ';', '*', '^', '$', '%', '!', '#', '@', '`', '~', '(', ')', '{', '}', ',', '.', '"', '/', '  ');
    foreach ($skip_char as $skip)
    {
      $urlname = str_replace($skip, '', $urlname);
    }

    return $urlname;
  }

}
