<?php

namespace MadeByPrisma;

use SilverStripe\Core\Config\Config;

class Seer {
	public static function log(string $bucket, string $payload) {
		$config = Config::inst();

		if ($app = $config->get(Seer::class, "app")) {
			if ($key = $config->get(Seer::class, "key")) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, "https://seer.madebyprisma.com");
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, [
					"seer-app: $app",
					"seer-key: $key",
					"seer-bucket: $bucket",
					"Content-Type: text/plain"
				]);

				$result = curl_exec($curl);
				$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);

				if ($code === 200) {
					return true;
				}
				else {
					throw new \Exception("Seer returned HTTP with code $code, $result");
				}
			}
			else {
				throw new \Exception("No key set");
			}
		}
		else {
			throw new \Exception("No app defined in config");
		}
	}
}