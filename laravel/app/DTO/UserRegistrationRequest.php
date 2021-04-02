<?php


namespace App\DTO;


class UserRegistrationRequest
{
    private string $username;
    private string $password;
    private string $about;

    /**
     * UserRegistrationData constructor.
     * @param string $username
     * @param string $password
     * @param string $about
     */
    public function __construct(string $username, string $password, string $about)
    {
        $this->username = $username;
        $this->password = $password;
        $this->about = $about;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about;
    }
}
