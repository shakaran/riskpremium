<?php
/**
 * Risk premium is a easy PHP API class for follow the main values of Risk premium
 * on Europe.
 * 
 * For more info:
 * Risk premium: http://en.wikipedia.org/wiki/Risk_premium
 * Von Neumann–Morgenstern utility theorem: http://en.wikipedia.org/wiki/Von_Neumann%E2%80%93Morgenstern_utility_theorem
 * 
 * @license 	AGPLv3 http://www.gnu.org/licenses/agpl-3.0.html 
 * 
 * RiskPremium is free software; you can redistribute it and/or modify
 * it under the terms of the Affero GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * FreeStation is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Fail2Ban; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * @author 		Ángel Guzmán Maeso <shakaran@gmail.com>
 * @version 	1.0
 * @category	Core
 * @package 	Api
 * @link        http://github.com/shakaran/riskpremium
 * @uses 		twitteroauth https://github.com/abraham/twitteroauth
 */
class RiskPremium
{
	/** @constant CONTENT_URL Content data extracted from some url page */
	const CONTENT_URL          = 'http://www.infobolsa.es/primas-riesgo.htm';
	/** @constant NO_VALUE value for represent no data */
	const NO_VALUE             = -1;
	
	/** @var string $content stores the html data */
	private static $content    = NULL;
	/** @var array $valid_keys params for get different statistics from data */
	private static $valid_keys = array('value', 'difference', 'percentage', 'max_year', 'min_year', 'all');
	/** @var array $risk_data values for each country. Store expression for keys and method name */
	private static $risk_data  = array(
										  // Ireland
										 'ir' => array(
										 				'method'     => 'getIreland',
										 				'expression' => array(
																 				'value'      => ' id="LV335.RBPLIR .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLIR .D240">(.*)</td>', // Difference
																			    'percentage' => ' id="LV335.RBPLIR .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLIR .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLIR .D1255">(.*)</td>', // Min year
																			 ),
										 			   ),
										  // Portugal
										 'pt' => array(
										 				'method'     => 'getPortugal',
										 				'expression' => array(
																 				'value'      => ' id="LV335.RBPLPT .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLPT .D240">(.*)</td>', // Difference
																			    'percentage' => ' id="LV335.RBPLPT .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLPT .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLPT .D1255">(.*)</td>', // Min year
																			 ),
										 			   ),
										// Finland
										'fi' => array(
											 			'method'     => 'getFinland',
											 			'expression' => array(
																	 			'value'      => ' id="LV335.RBPLFI .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLFI .D240">(.*)</td>', // Difference
																				'percentage' => ' id="LV335.RBPLFI .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLFI .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLFI .D1255">(.*)</td>', // Min year
																			 ),
														),
										// Belgium
										'be' => array(
												 		'method'     => 'getBelgium',
												 		'expression' => array(
																		 		'value'      => ' id="LV335.RBPLBE .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLBE .D240">(.*)</td>', // Difference
																				'percentage' => ' id="LV335.RBPLBE .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLBE .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLBE .D1255">(.*)</td>', // Min year
																			),
														),
										// Austria
										'at' => array(
													 	'method'     => 'getAustria',
													 	'expression' => array(
																			 	'value'      => ' id="LV335.RBPLAT .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLAT .D240">(.*)</td>', // Difference
																				'percentage' => ' id="LV335.RBPLAT .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLAT .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLAT .D1255">(.*)</td>', // Min year
																			),
														),

										// Holland
										'nl' => array(
														 'method'     => 'getHolland',
														 'expression' => array(
																				'value'      => ' id="LV335.RBPLNL .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLNL .D240">(.*)</td>', // Difference
																				'percentage' => ' id="LV335.RBPLNL .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLNL .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLNL .D1255">(.*)</td>', // Min year
																			),
														),
										 
										  // Spain
										 'es' => array(
										 				'method'     => 'getSpain',
										 				'expression' => array(
																 				'value'      => ' id="LV335.RBPLES .D420">(.*)</td>', // Value
																				'difference' => ' id="LV335.RBPLES .D240">(.*)</td>', // Difference
																			    'percentage' => ' id="LV335.RBPLES .D250">(.*)</td>', // Percentage
																				'max_year'  => ' id="LV335.RBPLES .D1245">(.*)</td>', // Max year
																				'min_year'  => ' id="LV335.RBPLES .D1255">(.*)</td>', // Min year
																			 ),
										 			   ),
										// France
										'fr' => array(
											 				'method'     => 'getFrance',
											 				'expression' => array(
																	 				'value'      => ' id="LV335.RBPLFR .D420">(.*)</td>', // Value
																					'difference' => ' id="LV335.RBPLFR .D240">(.*)</td>', // Difference
																				    'percentage' => ' id="LV335.RBPLFR .D250">(.*)</td>', // Percentage
																					'max_year'  => ' id="LV335.RBPLFR .D1245">(.*)</td>', // Max year
																					'min_year'  => ' id="LV335.RBPLFR .D1255">(.*)</td>', // Min year
																			),
														),										 			   
										// Italy
										'it' => array(
											 				'method'     => 'getItaly',
											 				'expression' => array(
																	 				'value'      => ' id="LV335.RBPLIT .D420">(.*)</td>', // Value
																					'difference' => ' id="LV335.RBPLIT .D240">(.*)</td>', // Difference
																				    'percentage' => ' id="LV335.RBPLIT .D250">(.*)</td>', // Percentage
																					'max_year'  => ' id="LV335.RBPLIT .D1245">(.*)</td>', // Max year
																					'min_year'  => ' id="LV335.RBPLIT .D1255">(.*)</td>', // Min year
																			),
														),											
										// Greece
										'gr' => array(
											 				'method'     => 'getGreece',
											 				'expression' => array(
																	 				'value'      => ' id="LV335.RBPLGR .D420">(.*)</td>', // Value
																					'difference' => ' id="LV335.RBPLGR .D240">(.*)</td>', // Difference
																				    'percentage' => ' id="LV335.RBPLGR .D250">(.*)</td>', // Percentage
																					'max_year'  => ' id="LV335.RBPLGR .D1245">(.*)</td>', // Max year
																					'min_year'  => ' id="LV335.RBPLGR .D1255">(.*)</td>', // Min year
																			),
														),
									   );
									   
	/**
	 * Build the main object and store html content.
	 * 
	 * @author Ángel Guzmán Maeso <shakaran@gmail.com>
	 * @return void
	 */
	public function __construct()
	{
		self::$content = file_get_contents(self::CONTENT_URL);
		
		if(empty(self::$content))
		{
			self::$content = self::NO_VALUE;
		}
	}
	
	/**
	* Validate a key for prepared on data.
	* 
	* Valid keys are $valid_keys.
	* 
	* @see $valid_keys
	* @author Ángel Guzmán Maeso <shakaran@gmail.com>
	* @access private
	* @return string The validated key
	*/
	private static function validate($key)
	{
		return in_array($key, self::$valid_keys) ? $key : 'value';
	}
	
	/**
	* Parse a expression searching useful data on content.
	*
	* Valid keys are $valid_keys.
	*
	* @see $risk_data
	* @author Ángel Guzmán Maeso <shakaran@gmail.com>
	* @access private
	* @return mixed The risk prime data parsed on spanish number format or 0 if no data.
	*/
	private static function parse($expression)
	{
		$data = NULL;

		if(self::$content !== self::NO_VALUE && preg_match('@' . $expression . '@i', self::$content, $matches))
		{
		    $data = $matches[1]; // Value as string on spanish money format
		}
		else
		{
		    $data = 0; // Fallback mode as zero value
		}
		
		return $data;
	}
	
	/**
	* Meta dinamic method called for each country.
	* 
	* Obtain the info needed for a country and key.
	* 
	* If no key is given, the defaul key is value.
	*
	* Valid keys are $valid_keys.
	*
	* @see $risk_data
	* @see $valid_keys
	* @author Ángel Guzmán Maeso <shakaran@gmail.com>
	* @access private
	* @return mixed The risk prime data parsed on spanish number format or 0 if no data.
	*/
	private static function getData($country = 'es', $key = 'value')
	{
		if(is_array($key))
		{
			$key = isset($key[0]) ? $key[0] : 'value';
		}
		
		$key = self::validate($key);
		
		// Check cached results
		if(!isset(self::$risk_data[$country][$key]))
		{
			// Process all valid keys
			if($key === 'all')
			{
				self::$risk_data[$country]['all'] = array();
				
				foreach(self::$risk_data[$country]['expression'][$key] as $expression)
				{
					self::$risk_data[$country]['all'][$key] = self::parse($expression);
				}
			}
			else // Process a valid key
			{
				$expression = self::$risk_data[$country]['expression'][$key];

				self::$risk_data[$country][$key] = self::parse($expression);
			}
		}

		return self::$risk_data[$country][$key];
	}
	
	/**
	* It is called when a public method is accepted for a country.
	* 
	* Parse the values dinamic called or show a message error if the
	* method doesn't exist.
	*
	* If no key is given, the defaul key is value.
	*
	* Valid keys are $valid_keys.
	*
	* @see $risk_data
	* @see $valid_keys
	* @author Ángel Guzmán Maeso <shakaran@gmail.com>
	* @access private
	* @return mixed The risk prime data parsed on spanish number format or 0 if no data.
	*/
	public function __call($method_name = 'getSpain', $key = 'value')
	{ 
		// Validate the method name
		$country = NULL;
		foreach(self::$risk_data as $country_name => $metadata)
		{
		    if(self::$risk_data[$country_name]['method'] === $method_name)
		    {
		    	$country = $country_name;
		    }
		}
		
		if(empty($country))
		{
			echo 'Error: risk premium for method ' . $method_name . ' not implemented';
		}
		else
		{
			return call_user_func('RiskPremium::getData', $country, $key);
		}
	}
}

/* Examples */

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