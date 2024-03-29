<?php
/*
 * This file is part of the BotGuard PHP API Connector.
 *
 * (c) 2018 Dennis Prochko <wolfsoft@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BotGuard;

/**
  * The class contains the user profile.
  *
  * Each time BotGuard Cloud processes the user request, it returns additional information
  * about user, a.k.a "user profile".
  *
  */
class Profile {

	private $headers;

/**
  * Constructor.
  *
  * Usually, you no need to call it directly.
  *
  * @param string $http_response Raw http server response.
  */
	public function __construct($http_response) {
		$this->headers = [];
		$lines = array_filter(array_map('trim', explode("\n", $http_response)));
		array_walk($lines, function($x) {
			$items = array_map('trim', explode(":", $x, 2));
			if (count($items) == 2) {
				$this->headers[$items[0]] = $items[1];
			} else {
				$this->headers[0] = $items[0];
			}
		});
	}

/**
  * Returns the Bot Score.
  *
  * BotGuard Cloud screens each request and scores it based on its characteristics.
  * By default, BotGuard considers the following levels of score:
  *
  * Score range:
  *
  * 0     Human user or "good" bot
  *
  * 1..4  We are in doubts; challenge required
  *
  * 5..n  Malicious bot
  *
  * @return int The score.
  */
	public function getScore() {
		return isset($this->headers['X-Score']) ? intval($this->headers['X-Score']) : null;
	}

}
