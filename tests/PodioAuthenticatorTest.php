<?php

    use PHPUnit\Framework\TestCase;
    use PodioAuthenticator;

    class PodioAuthenticatorTest extends TestCase {
        public function testAuthenticate(): void {
            $authenticator = new PodioAuthenticator();
            $this->assertTrue(Podio::is_authenticated());
        }
    }

?>