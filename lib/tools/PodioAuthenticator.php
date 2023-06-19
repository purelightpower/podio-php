<?php

    namespace Podio\Tools;

    use Podio\Podio;

    class PodioAuthenticator {
        const USER_TYPE = "user";
        const APP_TYPE = "app";
        
        private array $config;
        protected string $type;
        protected string $clientId;
        protected string $clientSecret;
        protected ?string $username;
        protected ?string $password;
        protected ?int $appId;
        protected ?string $appToken;

        public function __construct(string $configFile = null) {
            if (!empty($configFile)) {
                if (file_exists($configFile)) {
                    $this->loadConfig($configFile);
                } else {
                    throw new \Exception("No config file was found at $configFile");
                }
            } else {
                $defaultConfigLocation = self::getProjectRoot() . "/podio.json";
                if (file_exists($defaultConfigLocation)) {
                    $this->loadConfig($defaultConfigLocation);
                } else {
                    throw new \Exception("No config file path was passed to the PodioAuthenticator constructor and no config file was found at $defaultConfigLocation.");
                }
            }
            $this->authenticate();
        }

        protected function authenticate(): void {
            Podio::setup($this->clientId, $this->clientSecret);
            if ($this->isAppAuth()) {
                $this->authenticateApp();
            } else if ($this->isUserAuth()) {
                $this->authenticateUser();
            } else {
                throw new \Exception("The PodioAuthenticator can only authenticate using the app method or the user method.");
            }
        }

        private function isAppAuth(): bool {
            return $this->type === self::APP_TYPE;
        }

        private function isUserAuth(): bool {
            return $this->type === self::USER_TYPE;
        }

        private function authenticateApp(): void {
            if (empty($this->appId)) {
                throw new \Exception("An app id must be provided to authenticate Podio using the app authentication method.");
            }
            if (empty($this->appToken)) {
                throw new \Exception("An app token must be provided to authenticate Podio using the app authentication method.");
            }
            Podio::authenticate_with_app($this->appId, $this->appToken);
        }

        private function authenticateUser(): void {
            if (empty($this->username)) {
                throw new \Exception("A username must be provided to authenticate Podio using the user authentication method.");
            }
            if (empty($this->password)) {
                throw new \Exception("A password must be provided to authenticate Podio using the user authentication method.");
            }
            Podio::authenticate_with_password($this->username, $this->password);
        }

        private function loadConfig(string $filePath): void {
            $jsonString = file_get_contents($filePath);
            $this->config = json_decode($jsonString, true);
            $this->parseType();
            $this->parseClientCredentials();
            $this->parseAppCredentials();
            $this->parseUserCredentials();
        }

        private function parseType(): void {
            if (array_key_exists("type", $this->config)) {
                if (in_array($this->config["type"], [self::USER_TYPE, self::APP_TYPE])) {
                    $this->type = $this->config["type"];
                } else {
                    throw new \Exception("The Podio Authenticator does not support {${$this->config['type']}} authentication. Only \"app\" and \"user\" authentication types are supported.");
                }
            } else {
                throw new \Exception("No authentication type was provided in the Podio config file.");
            }
        }

        private function parseClientCredentials(): void {
            if (array_key_exists("credentials", $this->config)) {
                if (gettype($this->config["credentials"]) === "array") {
                    if (array_key_exists("client", $this->config["credentials"])) {
                        if (gettype($this->config["credentials"]["client"]) === "array") {
                            if (array_key_exists("id", $this->config["credentials"]["client"])) {
                                if (gettype($this->config["credentials"]["client"]["id"]) === "string") {
                                    $this->clientId = $this->config["credentials"]["client"]["id"];
                                } else {
                                    throw new \Exception("The client id in the Podio config file must be a string.");
                                }
                            } else {
                                throw new \Exception("A client id must be provided in the Podio config file.");
                            }
                            if (array_key_exists("secret", $this->config["credentials"]["client"])) {
                                if (gettype($this->config["credentials"]["client"]["secret"]) === "string") {
                                    $this->clientSecret = $this->config["credentials"]["client"]["secret"];
                                } else {
                                    throw new \Exception("The client secret in the Podio config file must be a string.");
                                }
                            } else {
                                throw new \Exception("A client secret must be provided in the Podio config file.");
                            }
                        } else {
                            throw new \Exception("A client id and client secret must be provided in the Podio config file.");
                        }
                    } else {
                        throw new \Exception("A client id and client secret must be provided in the Podio config file.");
                    }
                } else {
                    throw new \Exception("Credentials must be provided in the Podio config file.");
                }
            } else {
                throw new \Exception("Credentials must be provided in the Podio config file.");
            }
        }

        private function parseAppCredentials(): void {
            if (array_key_exists("credentials", $this->config)) {
                if (gettype($this->config["credentials"]) === "array") {
                    if (array_key_exists("app", $this->config["credentials"])) {
                        if (gettype($this->config["credentials"]["app"]) === "array") {
                            if (array_key_exists("id", $this->config["credentials"]["app"])) {
                                if (gettype($this->config["credentials"]["app"]["id"]) === "integer") {
                                    $this->appId = $this->config["credentials"]["app"]["id"];
                                } else {
                                    throw new \Exception("The app id in the Podio config file must be an integer.");
                                }
                            } else {
                                throw new \Exception("An app id must be provided in the Podio config file.");
                            }
                            if (array_key_exists("token", $this->config["credentials"]["app"])) {
                                if (gettype($this->config["credentials"]["app"]["token"]) === "string") {
                                    $this->appToken = $this->config["credentials"]["app"]["token"];
                                } else {
                                    throw new \Exception("The app token in the Podio config file must be a string.");
                                }
                            } else {
                                throw new \Exception("An app token must be provided in the Podio config file.");
                            }
                        } else {
                            $this->appId = null;
                            $this->appToken = null;
                        }
                    } else {
                        $this->appId = null;
                        $this->appToken = null;
                    }
                } else {
                    throw new \Exception("Credentials must be provided in the Podio config file.");
                }
            } else {
                throw new \Exception("Credentials must be provided in the Podio config file.");
            }
        }

        private function parseUserCredentials(): void {
            if (array_key_exists("credentials", $this->config)) {
                if (gettype($this->config["credentials"]) === "array") {
                    if (array_key_exists("username", $this->config["credentials"])) {
                        if (gettype($this->config["credentials"]["username"]) === "string") {
                            $this->username = $this->config["credentials"]["username"];
                        } else {
                            throw new \Exception("The username in the Podio config file must be a string.");
                        }
                    } else {
                        $this->username = null;
                    }
                    if (array_key_exists("password", $this->config["credentials"])) {
                        if (gettype($this->config["credentials"]["password"]) === "string") {
                            $this->password = $this->config["credentials"]["password"];
                        } else {
                            throw new \Exception("The password in the Podio config file must be a string.");
                        }
                    } else {
                        $this->password = null;
                    }
                } else {
                    throw new \Exception("Credentials must be provided in the Podio config file.");
                }
            } else {
                throw new \Exception("Credentials must be provided in the Podio config file.");
            }
        }

        private static function getProjectRoot(): string {
            $libraryRoot = dirname(__FILE__, 3);
            if (file_exists($libraryRoot . "/vendor/autoload.php")) {
                return $libraryRoot;
            } else {
                $projectRoot = dirname($libraryRoot, 3);
                if (file_exists($projectRoot . "/vendor/autoload.php")) {
                    return $projectRoot;
                } else {
                    throw new \Exception("We could not find the root directory of the project.");
                }
            }
        }
    }

?>