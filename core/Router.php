<?php
	/**
	 * Routes incoming requests to the appropriate objects and
	 * action, and then sends the object back to the 
	 * RequestHandler.
	 */
	class Router {
		private $routes;

		private $uri;

		private $_get     = array();
		private $_post    = array();
		private $_put     = array();
		private $_delete  = array();

		private $match;
		private $params;

		private $patterns = array(
			'/\[\(a\):[a-zA-Z0-9\_\-]+\]/',                            // [(a):param]
			'/\[\(a\+\):[a-zA-Z0-9\_\-]+\]/',                          // [(a+):param]
			'/\[\(i\):[a-zA-Z0-9\_\-]+\]/',                            // [(i):param]
			'/\[\(a\_\):[a-zA-Z0-9\_\-]+\]/',                          // [(a_):param]
			'/\[\(a\_\+\):[a-zA-Z0-9\_\-]+\]/',                        // [(a_+):param]
			'/\[:[a-zA-Z0-9\_\-]+\]/',                                 // [:param]
			'/\[:[a-zA-Z0-9\_\-]+=(\(([a-zA-Z0-9\_\-]+\|?)+\))\]/',    // [:param=(this|that)]
//			'/\[(\(([a-zA-Z0-9\_\-]+\|?)+\))\]/',                      // [(this|that)]
			'/\[:[a-zA-Z0-9\_\-]+!=(([a-zA-Z0-9\_\-]+)+)\]/'           // [:param!=this]
		);

		private $replacements = array(
			'([a-zA-Z]+)',                                             // [(a):param]
			'([a-zA-Z0-9]+)',                                          // [(a+):param]
			'([0-9]+)',                                                // [(i):param]
			'([a-zA-Z\_\-]+)',                                         // [(a_):param]
			'([a-zA-Z0-9\_\-]+)',                                      // [(a_+):param]
			'([a-zA-Z0-9\_\-]+)',                                      // [:param]
			'$1',                                                      // [:param=(this|that)]
//			'$1',                                                      // [(this|that)]
			'(?!$1)'                                                   // [:param!=this]
		);

		public function __construct($url, $header = 'GET') {
			// include API_CONFIG . 'routes.php';
			include (BASE_ROOT . 'config' . DS . 'routes.php');

			$this->routes = $routes;
			$this->uri = $url;

			try {
				$arr = $this->segmentRoutes();
				$this->matchRoute($header);
			} catch(Exception $e) {
				throw $e;
			}
		}

		/**
		 * Separates out Request types from routes.
		 */
		private function segmentRoutes() {
			foreach($this->routes as $route) {
				if($route[0] == 'GET')
					$this->_get[] = $route;
				else if($route[0] == 'POST')
					$this->_post[] = $route;
				else if($route[0] == 'PUT')
					$this->_put[] = $route;
				else if($route[0] == 'DELETE')
					$this->_delete[] = $route;
			}
		}

		/**
		 * Identifies the current and most
		 * proper of routes.
		 */
		private function matchRoute($header = 'GET') {
			// Variably grab the array.
			$var = '_'.strtolower($header);
			$arr = $this->$var; // ex: $this->_get;

			try {
				if(isset($arr) && is_array($arr) && count($arr) > 0) {
					foreach($arr as $route) {

						// Build the match pattern, like a REGEX BOSS.
						$route_url = str_replace('/', '\/', $route[1]); 
						$r = "@^" . preg_replace($this->patterns, $this->replacements, $route_url) . "$@D";
						$matches = array();

						if(preg_match($r, $this->uri, $matches)) {
							array_shift($matches);

							$params = array();
							$p      = array();

							// Grab the proper parameter IDs and assigns the right value.
							// This is the reason I can't do cool dynamic things without
							// named parameters, dudes.
							if(preg_match_all('/\:[a-zA-Z0-9\_\-]+/', $route[1], $params)) {
								$params = $params[0];
								for($i = 0; $i < count($params); $i++) {
									$p[str_replace(':', '', $params[$i])] = $matches[$i];
								}

								$this->params = $params;
							}

							$this->match = $route;
						}
					}

					if(!isset($this->match) || $this->match == '')
						// throw new NotFoundException('That URI does not exist.');
						echo 'That URI does not exist.';
				} else
					// throw new NotFoundException('That URI does not exist.');
				echo 'Issue with array or array empty ';
			} catch(NotFoundException $e) {
				throw $e;
			}
		}

		/**
		 * Returns the current match if the match exists.
		 * @return array An array, with the values match and parameters.
		 */
		public function getMatch() {
			if(!isset($this->match) || $this->match == '')
				throw new NotFoundException('That URI does not exist.');

			return array('match' => $this->match, 'parameters' => $this->params);
		}
	}

	// $router = new Router(CURRENT_URL);
	// 	$getMatch = $router->getMatch();
	// 	echo '<pre>'; print_r($getMatch);
		// echo 'Class: ' . $getMatch['match'][2] . '<br>Method: ' . $getMatch['match'][3];