This is still a work in progress, but it should work just fine. Let me know if you notice any issues. 

### Usage 
First, include the class in your project. 

`require 'Envato_marketplaces.php';`

Next, create a new instance of the class:

`$Envato = new Envato_marketplaces();`

And that's really it. You now have access to allow of the available functions. 

### Examples (to be updated)
#### Search for Sliders Across all Marketplaces

    $sliders = $Envato->search('sliders');
    $Envato->prettyPrint($sliders);

##### Limit results to a particualr site...
    
    $sliders = $Envato->search('sliders', 'codecanyon');
    $Envato->prettyPrint($sliders);
