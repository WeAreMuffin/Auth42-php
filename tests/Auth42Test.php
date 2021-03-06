<?php

require '../Auth42.php';

class Auth42Test extends PHPUnit_Framework_TestCase
{

    /**
     * @var Auth42
     */
    protected $object;
    protected $user = "";
    protected $password = "";

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp ()
    {
        $this->object = new Auth42();
    }

    /**
     * @covers Auth42::addOrReplaceDn
     * @covers Auth42::getDn
     */
    public function testAddOrReplaceDn ()
    {
        assert ($this->object->getDn () != NULL);
        assert (is_array ($this->object->getDn ()) == true);

        $dn = $this->object->getDn ();
        assert (array_key_exists ("dc", $dn) == true);
        assert (array_key_exists ("country", $dn) == false);

        $this->object->addOrReplaceDn ("country", "france");
        $dn = $this->object->getDn ();
        assert (array_key_exists ("country", $dn) == true);

        $this->object->addOrReplaceDn ("likes", array ("coffee", "nut"));
        $dn = $this->object->getDn ();
        assert (array_key_exists ("likes", $dn) == true);
    }

    /**
     * @covers Auth42::bind
     * @covers Auth42::setPassword
     * @covers Auth42::addOrReplaceDn
     */
    public function testGoodBind ()
    {
        $this->object->setPassword ($this->password);
        $this->object->setDn (array (
            "uid" => $this->user,
            "ou" => array ("2013", "people"),
            "dc" => array ("42", "fr")
        ));

        $result = $this->object->bind ();
        assert (
                $result, "Bind test. Your personnal 42 ids have to be set "
                . "at lines 11 & 12 to check this test"
        );
    }

    /**
     * @covers Auth42::bind
     * @covers Auth42::setPassword
     * @covers Auth42::addOrReplaceDn
     * @expectedException Auth42Exception
     */
    public function testBadBind ()
    {
        $this->object->setPassword ($this->password);
        $this->object->addOrReplaceDn ("uid", $this->user);

        $this->object->addOrReplaceDn ("uid", "an-user-wich-doesn-t-exists");
        $result = $this->object->bind ();
        assert ($result == false);
    }

    /**
     * @covers Auth42::authenticate
     * @covers Auth42::setPassword
     * @covers Auth42::addOrReplaceDn
     * @covers Auth42::bind
     */
    public function testGoodAuthenticate ()
    {
        $result = $this->object->authenticate ($this->user, $this->password);
        assert ($result == true, "Auth test. Your personnal 42 ids "
                . "have to be set at lines 11 & 12 to check this test");
    }

    /**
     * @covers Auth42::authenticate
     * @covers Auth42::setPassword
     * @covers Auth42::addOrReplaceDn
     * @covers Auth42::bind
     */
    public function testBadAuthenticate ()
    {
        $result = $this->object->authenticate ($this->user, "a-asdadsvsx-pass");
        assert ($result === false);
        $result = $this->object->authenticate ("DrBabar", $this->password);
        assert ($result === false);
        $result = $this->object->authenticate ("DrBabar", "a-asdadsvsx-pass");
        assert ($result === false);
    }

    /**
     * @covers Auth42::removeDn
     * @covers Auth42::addOrReplaceDn
     */
    public function testRemoveDn ()
    {
        $dn = $this->object->getDn ();
        assert (array_key_exists ("dc", $dn) == true);
        assert (array_key_exists ("country", $dn) == false);
        $this->object->removeDn ("dc");
        $dn = $this->object->getDn ();
        assert (array_key_exists ("dc", $dn) == false);

        $this->object->addOrReplaceDn ("country", "france");
        $dn = $this->object->getDn ();
        assert (array_key_exists ("country", $dn) == true);
        $this->object->removeDn ("country");
        $dn = $this->object->getDn ();
        assert (array_key_exists ("country", $dn) == false);

        $this->object->addOrReplaceDn ("likes", array ("coffee", "nut"));
        $dn = $this->object->getDn ();
        assert (array_key_exists ("likes", $dn) == true);
        $this->object->removeDn ("likes");
        $dn = $this->object->getDn ();
        assert (array_key_exists ("likes", $dn) == false);
    }

}
