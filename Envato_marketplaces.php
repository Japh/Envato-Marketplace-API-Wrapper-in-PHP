<?php

/**
 * Wrapper class for the Envato marketplaces API.
 *
 * @author Jeffrey Way <jeffrey@envato.com>
 * @created April, 2011 
 * @license Do-whateva-ya-want-with-it
*/ 


class Envato_marketplaces {
   public $api_key;
   protected $public_url = 'http://marketplace.envato.com/api/edge/set.json';
   
  /**
   * Attach your API key. 
   *
   * @param string $api_key Can be accessed on the marketplaces via My Account 
   * -> My Settings -> API Key
   */
   public function set_api_key($api_key)
   {
      $this->api_key = $api_key;
   }

  /**
   * Retrieve the value of your API KEY, if needed.
   *
   * @return string The requested API Key.
   */
   public function get_api_key()
   {
      if ( ! isset($this->api_key) ) return 'No API Key is set.';
      return $this->api_key;
   }

   /**
    * Available sets => 'vitals', 'earnings-and-sales-by-month', 'statement', 'recent-sales', 'account', 'verify-purchase', 'download-purchase'
    * 
    */ 
   public function private_user_data($user_name, $set, $purchase_code = null)
   {
      if ( ! isset($this->api_key) ) return 'You have not set an api key yet.';
      if (! isset($set) ) return 'Missing parameters';

      $url = "http://marketplace.envato.com/api/edge/$user_name/$this->api_key/$set";
      if ( !is_null($purchase_code) ) $url .= ":$purchase_code";
      $url .= '.json';

      $result = $this->curl($url);

      if ( isset($result->error) ) return 'Username, API Key, or purchase code is invalid.';
      return $result->$set;
   }

  /**
   * Can be used to verify if a person did in fact purchase your item.
   *
   * @param $user_name Author's username.
   * @param $purchase_code - The buyer's purchase code. See Downloads page for 
   * receipt.
   * @return object|bool If purchased, returns an object containing the details.
   */ 
   public function verify_purchase($user_name, $purchase_code)
   {
      $validity = $this->private_user_data($user_name, 'verify-purchase', $purchase_code);
      return isset($validity->buyer) ? $validity : false;
   }

  /**
   * Helper method to retrieve the balance on your account.
   *
   * @param string $user_name The username attached to your API KEY.
   * @return string The balance in your account.
   */
   public function balance($user_name)
   {
      $vitals = $this->private_user_data($user_name, 'vitals');
      return $vitals->balance;
   }

  /**
   * Retrieve details for your most recent sales.
   *
   * @param string $user_name The username attached to your API KEY.
   * @param int $limit The number of sales to return.
   * @return array A list of your recent sales.
   */
   public function recent_sales($user_name, $limit = null)
   {
      $sales = $this->private_user_data($user_name, 'recent-sales');
      return $this->apply_limit($sales, $limit);
   }

  /**
   * Retrieve your account information -- balance, location, name, etc.
   *
   * @param string $user_name The username attached to your API KEY.
   * @return array A list of account information for the user.
   */
   public function account_information($user_name)
   {
      return $this->private_user_data($user_name, 'account');
   }

  /**
   * Grab quick monthly stats - number of sales, income, etc.
   *
   * @param string $user_name The username attached to your API KEY.
   * @param int $limit The number of months to return.
   * @return array A list of sales figures, ordered by month.
   */
   public function earnings_by_month($user_name, $limit = null)
   {
      $earnings = $this->private_user_data($user_name, 'earnings-and-sales-by-month');
      return $this->apply_limit($earnings, $limit);
   }

  /**
   * Generic method, to be used in combination with the marketplace API docs.
   *
   * @param string $user_name The user name of the seller to track.
   * @return array The returned data wrapped in an array.
   */
   public function public_user_data($user_name)
   {
      $url = preg_replace('/set/i', 'user:' . $user_name, $this->public_url);
      return $this->curl($url)->user;
   }

  /**
   * Returns the featured item, author, and free file for a given marketplace.
   *
   * @param string $marketplace_name The desired marketplace name. 
   * @return array The featured file, free file, and featured author for the 
   * given site.
   */
   public function featured($marketplace_name = 'themeforest')
   {
      $url = preg_replace('/set/i', 'features:' . $marketplace_name, $this->public_url);
      return $this->curl($url)->features;
   }

  /**
   * Retrieve the details for a specific marketplace item.
   *
   * @param string $item_id The id of the item you need information for. 
   * @return object Details for the given item.
   */
   public function item_details($item_id)
   {
      $url = preg_replace('/set/i', 'item:' . $item_id, $this->public_url);
      return $this->curl($url)->item;
   }

  /**
   * Returns new files from a specific marketplaces and category.
   *
   * @param string $marketplace_name The desired marketplace name.
   * @param string $category The name of the category you'd like to search.
   * @param int $limit The number of files to return.
   * @return array A list of ALL recent files.
   */
   public function new_files($marketplace_name = 'themeforest', $category = 'wordpress', $limit = null)
   {
      $url = preg_replace('/set/i', 'new-files:' . $marketplace_name . ','. $category, $this->public_url);
      $new_files = $this->curl($url)->{'new-files'}; 

      return $this->apply_limit($new_files, $limit);
   }

  /**
   * Similar to new_files, but focuses on a specific author's files.
   *
   * @param string $user_name The desired username.
   * @param string $marketplace_name The desired marketplace name.
   * @param int $limit The number of files to return.
   * @return array A list of recently added files by one user.
   */
   public function new_files_from_user($user_name, $marketplace_name = 'themeforest', $limit = null)
   {
      $url = preg_replace('/set/i', 'new-files-from-user:' . $user_name . ',' . $marketplace_name, $this->public_url);
      $new_files = $this->curl($url)->{'new-files-from-user'};

      // If a limit is passed, create new array from results with a count equal 
      // to the limit. 
      return $this->apply_limit($new_files, $limit);
   }
   
  /**
   * Helper function which automatically echos out a list of thumbnails 
   * + links. Use new_files_from_user for more control.
   *
   * @param string $user_name The username of the account you want to display 
   * thumbnails from.
   * @param string $marketplace_name The desired marketplace name.
   * @param int $limit The number of thumbnails to display.
   * @return string Helper function immediately echos out thumbnails. 
   * Careful...
   */
   public function display_thumbs($user_name, $marketplace_name, $limit = null)
   {
      $results = $this->new_files_from_user($user_name, $marketplace_name, $limit); 
      
      echo "<ul> \n";
      foreach($results as $item) : ?>
      <?php if ( is_null($item) ) break; ?>
      <li>
          <a href="<?php echo $item->url . "?ref=$user_name"; ?>">   
             <img src="<?php echo $item->thumbnail; ?>" />
          </a>
      </li>
      <?php endforeach;
      echo "\n</ul>";

   }

  /**
   * Retrieve the most popular files of the previous week.
   *
   * @param string $marketplace_name Desired marketplace name.
   * @param int $limit The number of items to return [optional].
   * @return array A list of the most sold items in the given marketplace last 
   * week.
   */
   public function most_popular_last_week($marketplace_name = 'themeforest', $limit = null)
   {
      $url = preg_replace('/set/i', 'popular:' . $marketplace_name, $this->public_url);
      $pop = $this->curl($url)->popular->items_last_week;
      return $this->apply_limit($pop, $limit);
   }

  /**
   * Perform search queries on all of the marketplaces, or a specific one. 
   *
   * @param string $search_expression What are you searching for?
   * @param string $marketplace_name The name of the marketplace you want to 
   * search. [optional] 
   * @param string $type The item type (category). See search options on 
   * marketplace for list. [optional]
   * @param integer $limit The number of items to return [optional]
   * @return array A list of the search results.
   */
   public function search($search_expression, $marketplace_name = '', $type = '', $limit = null)
   {
      if ( empty($search_expression) ) return false;
      # Can't use spaces. Need to replace them with pipes. 
      else $search_expression = preg_replace('/\s/', '|', $search_expression);

      $url = preg_replace('/set/i', 'search:' . $marketplace_name . ',' . $type . ',' . $search_expression, $this->public_url );
      $search_results = $this->curl($url)->search;
      return $this->apply_limit($search_results, $limit);
   }

  /**
   * Retrieves general marketplace member information.
   *
   * @param string $user_name The username to query.
   * @return object Contains the requested user information.
   */
   public function user_information($user_name)
   {
      $url = preg_replace('/set/i', 'user:' . $user_name, $this->public_url);
      return $this->curl($url)->user;
   }

  /**
   * Retrieve an array of all the items in a particular collection.
   *
   * @param string $collection_id The id of the requested collection. See url 
   * of collection page for id.
   * @return array A list of all the items in the collection.
   */
   public function collection($collection_id)
   {
      $url = preg_replace('/set/i', 'collection:' . $collection_id, $this->public_url);
      return $this->curl($url)->collection;
   }

  /**
   * Filters returned result, according to the supplied $limit.
   *
   * @param string $orig_arr The original array to work on.
   * @param int $limit Specifies the number of array items in the result.
   * @return array A new array with a count equal to the passed $limit.
   */
   protected function apply_limit($orig_arr, $limit)
   {
      if ( !is_int($limit) ) return $orig_arr;

      // Make sure that there are enough items to filter through... 
      if ( $limit > count($orig_arr) ) $limit = count($orig_arr);

      $new_arr = array();
      for ( $i = 0; $i <= $limit - 1; $i++ ) {
         $new_arr[] = $orig_arr[$i];
      }
      return $new_arr;
   }

  /**
   * General purpose function to query the marketplace API.
   *
   * @param string $url The url to access, via curl.
   * @return object The results of the curl request.
   */
   protected function curl($url) 
   {
      if ( empty($url) ) return false;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $data = curl_exec($ch);
      curl_close($ch);

      return json_decode($data);
   }

  /**
   * A simple convenience function to save a few seconds during development.
   *
   * @param $data The array or object to display on the page, for testing.
   */
   public function prettyPrint($data)
   {
      echo "<pre>";
      print_r($data);
      echo "</pre>"; 
   }
}
