<?php namespace Codengine\Notifier;

use Illuminate\Database\Eloquent\Model;

class Notification implements \ArrayAccess
{
    protected $view = null;
    protected $user = null;
    protected $subject = null;
    protected $action = null;
    protected $type = null;
    protected $view_data = array();

    public function __construct(Model $user, $view = null)
    {
        $this->user = $user;
        $this->view = $view;
    }

    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    public function setUser(Model $user)
    {
        $this->user = $user;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setAction($link)
    {
        $this->action = $link;
    }

    public function setViewData($view_data)
    {
        $this->view_data = $view_data;
        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getViewData()
    {
        return array_merge(
            array(
                'user' => $this->getUser(),
                'action' => $this->getAction(),
                'type' => $this->getType(),
                'subject' => $this->getSubject()
            ),
            $this->view_data
        );
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAction()
    {
        return $this->action;
    }

    // array access of view data
    public function offsetExists($offset)
    {
        return isset($this->view_data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->view_data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->view_data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->view_data[$offset]);
    }

}