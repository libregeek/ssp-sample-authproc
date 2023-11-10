<?php
namespace SimpleSAML\Module\beta\Controller;
use Exception;
use SimpleSAML\Auth;
use SimpleSAML\Configuration;
use SimpleSAML\Error;
use SimpleSAML\HTTP\RunnableResponse;
use SimpleSAML\IdP;
use SimpleSAML\Locale\Translate;
use SimpleSAML\Logger;
use SimpleSAML\Module;
use SimpleSAML\Session;
use SimpleSAML\Stats;
use SimpleSAML\Utils;
use SimpleSAML\XHTML\Template;
use Symfony\Component\HttpFoundation\Request;

class BetaController
{
    /** @var \SimpleSAML\Configuration */
    protected $config;

    /** @var \SimpleSAML\Session */
    protected $session;

    /**
     *      * @var \SimpleSAML\Auth\State|string
     *           * @psalm-var \SimpleSAML\Auth\State|class-string
     *                */
    protected $authState = Auth\State::class;

    /**
     *      * @var \SimpleSAML\Logger|string
     *           * @psalm-var \SimpleSAML\Logger|class-string
     *                */
    protected $logger = Logger::class;


    /**
     *      * ConsentController constructor.
     *           *
     *                * @param \SimpleSAML\Configuration $config The configuration to use.
     *                     * @param \SimpleSAML\Session $session The current user session.
     *                          */
    public function __construct(Configuration $config, Session $session)
    {
        $this->config = $config;
        $this->session = $session;
    }


    /*
     *  Inject the \SimpleSAML\Auth\State dependency.
     *
     * @param \SimpleSAML\Auth\State $authState
     */
    public function setAuthState(Auth\State $authState): void
    {
        $this->authState = $authState;
    }
    /*
     *   @param \SimpleSAML\Logger $logger
    */
    public function setLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }


    /*
     * Display mfa form.
     *           
     *  @param \Symfony\Component\HttpFoundation\Request $request The current request.
     *  
     * @return \SimpleSAML\XHTML\Template|\SimpleSAML\HTTP\RunnableResponse
     */
    public function authenticate(Request $request)
    {
        $stateId = $request->query->get('StateId');
        if ($stateId === null) {
            throw new Error\BadRequest('Missing required StateId query parameter.');
        }
        $state = $this->authState::loadState($stateId, 'beta:request');

        $attributes = $state['Attributes'];
        $uid = $attributes['uid'][0];
        // populate values for template
        $t = new Template($this->config, 'beta:authenticate.twig');
                    $state['mfa_option'] = "beta";
        $t->data['userError'] = "BETA Authentication Process Filter: ". $uid;
         $t->data['StateId'] = $stateId;
        if($request->query->get('submit') == "Submit") {  
                    return new RunnableResponse([Auth\ProcessingChain::class, 'resumeProcessing'], [$state]);
        } else {

        return $t;
        }
    }
}
?>
