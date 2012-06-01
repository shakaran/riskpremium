Risk premium is a easy PHP API class for follow the main values of Risk premium
on Europe. 

It is mainly used on Twitter bot @PrimaRiesgoBot_ https://twitter.com/PrimaRiesgoBot_

It is a improved version with more details of @PrimaRiesgoBot (without underscore).

It support also get the data for other european countries like:

   * Ireland
   * Portugal
   * Finland
   * Austria
   * Holland
   * Spain
   * France
   * Italy
   * Greece

As twitter bot uses twitteroauth https://github.com/abraham/twitteroauth 
tweeting the values obtained.

Risk premium is licensed under AGPLv3 http://www.gnu.org/licenses/agpl-3.0.html

The code is autodocumented with php-doc syntax and some examples at the end.

Some examples
-----------

    // Init the class
    $risk_premium = new RiskPremium();
    
    // Get data without keys for each country or specify value for normal behaviour
    $risk_premium_ireland = $risk_premium->getIreland();
    $risk_premium_portugal = $risk_premium->getPortugal();
    $risk_premium_finland = $risk_premium->getFinland();
    $risk_premium_belgium = $risk_premium->getBelgium();
    $risk_premium_austria = $risk_premium->getAustria();
    $risk_premium_holland = $risk_premium->getHolland();
    $risk_premium_spain   = $risk_premium->getSpain('value');
    $risk_premium_france  = $risk_premium->getFrance();
    $risk_premium_italy   = $risk_premium->getItaly();
    $risk_premium_greece  = $risk_premium->getGreece();
    
    // Show the data. See on Spain other interested data with other keys
    echo 'Ireland: ' . $risk_premium_ireland . '<br />' .
         'Portugal: ' . $risk_premium_portugal . '<br />' .
         'Finland: ' . $risk_premium_finland . '<br />' .
         'Belgium: ' . $risk_premium_belgium . '<br />' .
         'Austria: ' . $risk_premium_austria . '<br />' .
         'Holland: ' . $risk_premium_holland . '<br />' .
         'Spain: ' . $risk_premium_spain . ' Diff: ' . $risk_premium->getSpain('difference') . 
         ' % ' . $risk_premium->getSpain('percentage') . 
         ' Max year: ' . $risk_premium->getSpain('max_year') .
         ' Min year: ' . $risk_premium->getSpain('min_year')  .'<br />' .
         'France: ' . $risk_premium_france . '<br />' .
         'Italy: ' . $risk_premium_italy . '<br />' .
         'Greece: ' . $risk_premium_greece . '<br />';

Risk premium accept pull request or issues if you want improve or change something.

For more info:
 * Risk premium: http://en.wikipedia.org/wiki/Risk_premium
 * Von Neumann–Morgenstern utility theorem: http://en.wikipedia.org/wiki/Von_Neumann%E2%80%93Morgenstern_utility_theorem