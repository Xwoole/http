<?php

namespace Xwoole\Http\Functionality;

use OpenSwoole\Http\Response;
use Xwoole\Router\RouterRequest;
use Xwoole\Router\Routing;
use Xwoole\Session\Identifier\OpenswooleIdentifer;
use Xwoole\Session\Session;
use Xwoole\Session\Storage\Contract;

trait Server
{
    use Routing;
    
    public function enableSession(Contract $storage)
    {
        $this->withMiddleware(function(RouterRequest $request, Response $response, callable $next) use ($storage)
        {
            $identifier = new OpenswooleIdentifer($request, $response);
            $request->session = new Session($storage, $identifier);
            $request->session->start();
            $next($request, $response);
        });
    }
    
    #[\Override]
    public function start(): bool
    {
        $this->on("request", $this->generateOpenswooleHttpServerRequestHandler());
        return parent::start();
    }
    
}
