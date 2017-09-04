<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class YahooApiClient
{
	private $CI = null;
	
    public function __construct()
    {
        require_once APPPATH.'vendor/scheb/yahoo-finance-api/ApiClient.php';		
    }
	
	public function get_instance(){
		if ($this->CI == null){					
			$this->CI = new Scheb\YahooFinanceApi\ApiClient();
		}
		
		return $this->CI;
	}
}