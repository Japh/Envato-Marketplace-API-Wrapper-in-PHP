This is still a work in progress, but it should work just fine. Let me know if you notice any issues. 

### Usage 
First, include the class in your project. 

`require 'Envato_marketplaces.php';`

Next, create a new instance of the class:

`$Envato = new Envato_marketplaces();`

And that's really it. You now have access to all of the available functions. 

### Examples 

#### Quickly Echo Thumbnails of a User's Items
    $Envato = new Envato_marketplaces();
    $Envato->display_thumbs('your username', 'desired marketplace name', 'number to display');

#### Search for Sliders Across all Marketplaces
    $Envato = new Envato_marketplaces();
    $sliders = $Envato->search('sliders');
    $Envato->prettyPrint($sliders);

##### Limit results to a particular site...
    $Envato = new Envato_marketplaces();
    $sliders = $Envato->search('sliders', 'codecanyon');
    $Envato->prettyPrint($sliders);

##### Limit further to a marketplace category...
    $Envato = new Envato_marketplaces();
    $sliders = $Envato->search('sliders', 'codecanyon', 'plugins');
    $Envato->prettyPrint($sliders);

#### Get Account Balance
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $Envato->set_api_key('your api key');

    # Echo out the balance.
    echo $Envato->balance('your username');

#### Get Featured Item
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $featured = $Envato->featured('codecanyon');

    # Just for reviewing available props
    $Envato->prettyPrint($featured);

    # Featured author
    echo $featured->featured_author->user;

    # Featured file
    echo $featured->featured_file->url;

    #Free file
    echo $featured->free_file->url;

#### Get Specific Item Details
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();

    # Pass in item id -- see url on item page on the marketplace.
    $item = $Envato->item_details('232428');

    # Only for development purposes. Review available options.
    $Envato->prettyPrint($item);
    ?>

    <h2> <?php echo $item->item; ?></h2>
    <a href="<?php echo $item->url; ?>">
       <img src="<?php echo $item->live_preview_url; ?>" alt="<?php echo $item->item; ?>" />
    </a>

#### Display Your Own Recent Items
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
   
    # marketplace name, desired category, optional limit (number of items to return)
    $files = $Envato->new_files_from_user('Your Username', 'desired marketplace name', 5);

    #See what we got back...
    $Envato->prettyPrint($files);

    # Display thumbnails and links to the item pages.
    foreach($files as $item) : ?>
       <a href="<?php echo $item->url; ?>">   
          <img src="<?php echo $item->thumbnail; ?>" />
       </a>
    <?php endforeach; ?>

#### Get Your Recent Sales Data
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $Envato->set_api_key('your api key');
    $sales = $Envato->recent_sales('your username', 'optional limit');

    # Review what we got back
    $Envato->prettyPrint($sales);

#### Get Earnings By Month
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $Envato->set_api_key('your api key');

    $monthly_earnings = $Envato->earnings_by_month('your username', 'optional limit');

    # For review...
    $Envato->prettyPrint($monthly_earnings);

#### Get Your Account Info
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $Envato->set_api_key('your api key');

    $account_info = $Envato->account_information('your username');

    # See what we got back....
    $Envato->prettyPrint($account_info);

#### Get Trending Items on Marketplace
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    
    $pop = $Envato->most_popular_last_week('marketplace name', 'optional limit');

    # Result Array
    $Envato->prettyPrint($pop);

#### Verify a Purchase
    require 'Envato_marketplaces.php';
    $Envato = new Envato_marketplaces();
    $Envato->set_api_key('your api key');

    // Ensure that somebody bought your item.
    // If successful, $verify will be an object which
    // contains all of the purchase information.
    $verify = $Envato->verify_purchase('your username', 'buyer purchase code');

    // Quickie test. 
    if ( isset($verify->buyer) ) echo 'bought';
    else echo 'did not buy';

