This is still a work in progress, but it should work just fine. Let me know if you notice any issues. 

### Usage 
First, include the class in your project. 

`require 'Envato_marketplaces.php';`

Next, create a new instance of the class:

`$Envato = new Envato_marketplaces();`

And that's really it. You now have access to all of the available functions. 

### Examples (to be updated)
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
    echo $Envato->balance('JeffreyWay');

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



   

