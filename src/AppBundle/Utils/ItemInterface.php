<?php
namespace AppBundle\Utils;

interface ItemInterface
{
    public function isPublic();
    public function setPublic($public = true);
    public function getAddedBy();
    public function setAddedBy($addedBy);
    public function getEditedBy();
    public function setEditedBy($editedBy);
}