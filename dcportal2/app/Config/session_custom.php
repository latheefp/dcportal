ini_set('session.referer_check', '');                    // Killing this f***ing config that was causing so much trouble with Chrome
ini_set('session.use_trans_sid', 0);                    // No session id in url
ini_set('session.name', Configure::read('Session.cookie'));    // Using custom cookie name instead of PHPSESSID
ini_set('session.cookie_lifetime', $this->cookieLifeTime);    // Cookie like time, depending on security level
ini_set('session.cookie_path', $this->path);                // Cookie path
