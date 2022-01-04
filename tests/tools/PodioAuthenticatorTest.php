<?php

    use PHPUnit\Framework\TestCase;
    use Podio\Podio;
    use Podio\Tools\PodioAuthenticator;

    class PodioAuthenticatorTest extends TestCase {
        public function testAuthenticate(): void {
            $authenticator = new PodioAuthenticator();
            $this->assertTrue(Podio::is_authenticated());
        }
    }

?>