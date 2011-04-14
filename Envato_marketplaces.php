<?php

class Envato_marketplaces {
   public $api_key;
   private $public_url = 'http://marketplace.envato.com/api/edge/set.json';

   public function set_api_key($api_key)
   {
      $this->api_key = $api_key;
   }

   public function get_api_key()
   {
      return $this->api_key;
   }
   /**
    * Available sets => 'vitals', 'earnings-and-sales-by-month', 'statement', 'recent-sales', 'account', 'verify-purchase', 'download-purchase'
    * 
    */ 
   public function private_user_data($user_name, $set)
   {
      if ( ! isset($this->api_key) ) return 'You have not set an api key yet.';
      if (! isset($set) ) return 'Missing parameters';

      $url = "http://marketplace.envato.com/api/edge/" .  $user_name . "/" . $this->api_key . "/" . $set . ".json";
      return $this->curl($url)->$set; 
   }

   public function public_user_data($user_name)
   {
      $url = preg_replace('/set/i', 'user:' . $user_name, $this->public_url);
      return $this->curl($url)->user;
   }

   public function featured($marketplace_name = 'themeforest')
   {
      $url = preg_replace('/set/i', 'features:' . $marketplace_name, $this->public_url);
      return $this->curl($url)->features;
   }

   public function item_details($item_id)
   {
      $url = preg_replace('/set/i', 'item:' . $item_id, $this->public_url);
      return $this->curl($url)->item;
   }

   public function new_files($marketplace_name = 'themeforest', $category = 'wordpress')
   {
      $url = preg_replace('/set/i', 'new-files:' . $marketplace_name . ','. $category, $this->public_url);
      return $this->curl($url)->{'new-files'};
   }

   public function new_files_from_user($user_name, $marketplace_name = 'themeforest')
   {
      $url = preg_replace('/set/i', 'new-files-from-user:' . $user_name . ',' . $marketplace_name, $this->public_url);
      return $this->curl($url)->{'new-files-from-user'};
   }

   public function most_popular_last_week($marketplace_name = 'themeforest')
   {
      $url = preg_replace('/set/i', 'popular:' . $marketplace_name, $this->public_url);
      return $this->curl($url)->popular->items_last_week;
   }

   // First two optional, third is required
   public function search($search_expression, $site_name = '', $type = '')
   {
      if ( empty($search_expression) ) return false;
      # Can't use spaces. Need to replace them with pipes. 
      else $search_expression = preg_replace('/\s/', '|', $search_expression);

      $url = preg_replace('/set/i', 'search:' . $site_name . ',' . $type . ',' . $search_expression, $this->public_url );
      return $this->curl($url)->search;
   }

   protected function curl($url) 
   {
      if ( empty($url) ) return false;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $data = curl_exec($ch);
      curl_close($ch);

      return json_decode($data);
   }

   public function prettyPrint($data)
   {
      echo "<pre>";
      print_r($data);
      echo "</pre>"; 
   }
}
