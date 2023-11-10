<?php
namespace SimpleSAML\Module\beta\Auth\Process;
use Exception;
use SAML2\Constants;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Auth;
use SimpleSAML\Error;
use SimpleSAML\Logger;
use SimpleSAML\Module;
use SimpleSAML\Module\consent\Store;
use SimpleSAML\Stats;
use SimpleSAML\Utils;

class Beta extends Auth\ProcessingFilter
{

    private $enforce_2fa = false;

    public function __construct($config, $reserved) {
        parent::__construct($config, $reserved);
        $this->name = "ALPHA";
    }

    public function process(Array &$state):void 
    {
        $httpUtils = new Utils\HTTP();
        $attributes = &$state['Attributes'];
        $id  = Auth\State::saveState($state, 'beta:request');
        $url = Module::getModuleURL('beta/authenticate');
        $httpUtils->redirectTrustedURL($url, array('StateId' => $id));
        return;
    }
}
